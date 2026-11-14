<?php
// Run against a FreshRSS checkout; all configuration and logs use a temporary directory.
declare(strict_types=1);

$root = getenv('FRESHRSS_PATH');
if (!$root || !is_file($root . '/constants.php')) {
    fwrite(STDERR, "Set FRESHRSS_PATH to a FreshRSS checkout.\n");
    exit(1);
}
$data = sys_get_temp_dir() . '/filter-title-test-' . bin2hex(random_bytes(8));
mkdir($data, 0700);
mkdir($data . '/users');
mkdir($data . '/users/_');
file_put_contents($data . '/config.php', '<?php return [];');
putenv('DATA_PATH=' . $data);
define('COPY_LOG_TO_SYSLOG', false);
define('COPY_SYSLOG_TO_STDERR', false);
require $root . '/constants.php';
require LIB_PATH . '/lib_rss.php';
require LIB_PATH . '/Minz/Url.php';
require dirname(__DIR__) . '/extension.php';
session_save_path($data);
Minz_Session::init('FilterTitleTest', true);
FreshRSS_Context::initSystem();
Minz_Translate::init('en');

register_shutdown_function(static function () use ($data): void {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($data, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $file) {
        $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
    }
    rmdir($data);
});

function check(bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function extension(): FilterTitleExtension {
    return new FilterTitleExtension([
        'name' => 'FilterTitle',
        'entrypoint' => 'FilterTitle',
        'path' => dirname(__DIR__),
        'type' => 'system',
    ]);
}

function activate(): FilterTitleExtension {
    Minz_ExtensionManager::init();
    $extension = extension();
    $extension->init();
    return $extension;
}

function filterEntry($entry) {
    return Minz_ExtensionManager::callHook('entry_before_insert', $entry);
}

function saveRules(array $rules, array $settings = []): FilterTitleExtension {
    $saved = submit($settings + [
        'blacklist' => '', 'whitelist' => '', 'mark_as_read' => '',
        'rules_present' => '1', 'rules' => $rules,
    ]);
    check($saved->getConfigurationErrors() === [], 'Rules must save: ' . implode('; ', $saved->getConfigurationErrors()));
    return activate();
}

function submit(array $params): FilterTitleExtension {
    $_SERVER['REQUEST_METHOD'] = 'POST';
    Minz_Request::_params($params);
    $extension = extension();
    $extension->handleConfigureAction();
    return $extension;
}

Minz_Request::_controllerName('extension');
extension()->init();
check(str_contains(Minz_View::headScript(), 'configure.js'), 'Load editor assets on the extensions list before opening the slider');
Minz_Request::_controllerName('');

$valid = ['blacklist' => '/Promo Codes?/i', 'whitelist' => '', 'mark_as_read' => ''];
submit($valid);
$before = file_get_contents(DATA_PATH . '/config.php');
$broken = '/\[Sponsor\]|Promo Codes?|Discount codes?/i|Coupons?/i';
$extension = submit(['blacklist' => $broken, 'whitelist' => '/keep/i', 'mark_as_read' => '1']);
check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Invalid regex must leave every saved setting unchanged');
echo "PASS invalid regex does not overwrite working settings\n";

$input = "/keep/i\n\n/</textarea><script>alert(1)</script>/";
$extension = submit(['blacklist' => $input, 'whitelist' => "\n/unclosed", 'mark_as_read' => '1']);
$view = $extension->getConfigureView();
check(str_contains($view, 'Blacklist, line 3: invalid regular expression'), 'Show the blacklist line that needs fixing');
check(str_contains($view, 'Whitelist, line 2: invalid regular expression'), 'Validate whitelist regexes too');
check(str_contains($view, htmlspecialchars($input, ENT_QUOTES, 'UTF-8')), 'Keep the submitted rules safely escaped for correction');
check(!str_contains($view, '<script>'), 'Rule text must not become HTML');
check((bool)preg_match('/id="mark_as_read"[^>]*checked/', $view), 'Keep the submitted checkbox state after an error');
check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Invalid whitelist must not partially save other settings');
echo "PASS invalid rules show line numbers and preserve editable input safely\n";

$extension = submit(['blacklist' => "\n  /unclosed  ", 'whitelist' => '', 'mark_as_read' => '']);
check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Leading spaces must not bypass slash-regex validation');
check(str_contains($extension->getConfigureView(), "\n  /unclosed  "), 'Keep whitespace and blank lines when redisplaying invalid rules');
echo "PASS whitespace cannot bypass validation\n";

set_error_handler(static function (int $severity, string $message): bool {
    if (error_reporting() & $severity) {
        throw new RuntimeException($message);
    }
    return false;
});
$rules = [
    '/\[Sponsor\]/i',
    '/Promo Codes?/i',
    '/Discount codes?/i',
    '/Coupons?/i',
    '/\bdiscounts for (January|February|March|April|May|June|July|August|September|October|November|December)\b/i',
    'some keyword',
    '~legacy delimiter~i',
    '/(?<=Loop )Earplugs/i',
    '/A&B/i',
    '0',
];
$extension = submit(['blacklist' => '  ' . implode("\r\n\r\n", $rules) . '  ', 'whitelist' => '', 'mark_as_read' => '']);
$extension = extension(); // Read back the persisted settings, not the submission cache.
foreach ([
    '[Sponsor] News',
    '30% Off Tempur-Pedic Promo Codes | November 2026',
    'Skullcandy Discount Code: 30% Off | November 2026',
    'Dermstore Coupons: 25% Off for November 2026',
    'Best discounts for November',
    'some keyword in a title',
    'A LEGACY DELIMITER story',
    'Loop Earplugs review',
    'A&B news',
    'Version 0 released',
] as $title) {
    check($extension->filterTitle(new FreshRSS_Entry(title: $title)) === null, 'Should block: ' . $title);
}
$ordinary = new FreshRSS_Entry(title: 'Most Servers on the Planet Are Vulnerable to This Firmware Code-Execution Flaw');
check($extension->filterTitle($ordinary) === $ordinary, 'Ordinary reporting must still enter the database');
echo "PASS saved rules block promotions and preserve plain keywords, legacy regexes and ordinary reporting\n";

submit(['blacklist' => '/Promo/i', 'whitelist' => "/Science/i\n/Space/i", 'mark_as_read' => '1']);
$extension = extension();
$promo = new FreshRSS_Entry(title: 'Promo Science Space');
check($extension->filterTitle($promo) === $promo && $promo->isRead() === true, 'Mark-as-read mode must still insert matching entries as read');
submit(['blacklist' => '/Promo/i', 'whitelist' => "/Science/i\n/Space/i", 'mark_as_read' => '']);
$extension = extension();
check($extension->filterTitle(new FreshRSS_Entry(title: 'Promo Science Space')) === null, 'Blacklist still takes priority');
check($extension->filterTitle(new FreshRSS_Entry(title: 'Science news')) === null, 'Every whitelist rule must match');
$allowed = new FreshRSS_Entry(title: 'Science Space news');
check($extension->filterTitle($allowed) === $allowed, 'Allow titles matching every whitelist rule');
$before = file_get_contents(DATA_PATH . '/config.php');
$extension = submit(['blacklist' => '/changed/i', 'whitelist' => '/broken[/', 'mark_as_read' => '1']);
check(file_get_contents(DATA_PATH . '/config.php') === $before, 'An invalid whitelist alone must prevent the entire save');
$_SERVER['REQUEST_METHOD'] = 'GET';
Minz_Request::_params(['blacklist' => '/changed/i']);
extension()->handleConfigureAction();
check(file_get_contents(DATA_PATH . '/config.php') === $before, 'GET requests must not save settings');
submit(['blacklist' => '', 'whitelist' => '', 'mark_as_read' => '']);
$extension = extension();
check($extension->filterTitle($promo) === $promo, 'Empty lists must remain supported');
echo "PASS whitelist, priority, mark-as-read, GET and empty-list behaviour is unchanged\n";

$before = file_get_contents(DATA_PATH . '/config.php');
foreach (glob(dirname(__DIR__) . '/i18n/*', GLOB_ONLYDIR) as $language) {
    Minz_Translate::reset(basename($language));
    $extension = submit(['blacklist' => "\n/unclosed", 'whitelist' => '', 'mark_as_read' => '']);
    check(str_contains($extension->getConfigureView(), _t('ext.filter_title.blacklist') . ', line 2:'),
        'Missing translations must retain field and line information: ' . basename($language));
    check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Every locale must reject invalid settings');
}
echo "PASS every shipped locale shows useful validation errors\n";

Minz_Translate::reset('en');
$extension = extension();
foreach ([null, false, 'not an entry', 0] as $value) {
    check($extension->filterTitle($value) === $value, 'Non-entry values pass through unchanged');
}
foreach (['0', '1'] as $checkType) {
    $settings = FreshRSS_Context::systemConf()->extensions;
    $settings['FilterTitle'] = [
        'check_type' => $checkType,
        'blacklist_title_keywords' => ['0', 'old keyword'],
    ];
    FreshRSS_Context::systemConf()->extensions = $settings;
    $reloaded = extension();
    $text = $checkType === '0' ? $reloaded->getBlacklistData() : $reloaded->getWhitelistData();
    check($text === "0\nold keyword", 'Old configuration getters remain compatible without migration');
    $entry = new FreshRSS_Entry(title: 'old keyword');
    check($reloaded->filterTitle($entry) === $entry, 'Old getter paths do not migrate legacy filtering settings');
}
echo "PASS absent rules, non-entry passthrough and old getter paths remain unchanged\n";

$authorRule = ['field' => 'authors', 'operator' => 'equals', 'value' => 'Sponsored Post', 'case_sensitive' => false];
foreach (['', '1'] as $action) {
    $extension = submit([
        'blacklist' => '', 'whitelist' => '', 'mark_as_read' => $action,
        'rules_present' => '1', 'rules' => [$authorRule],
    ]);
    check($extension->getConfigurationErrors() === [], 'Save an authors-equals rule');
    $extension = activate();
    check($extension->getSystemConfigurationValue('rules') === [$authorRule], 'Reload the saved author rule');
    check(str_contains($extension->getConfigureView(), 'Sponsored Post'), 'Render the saved author rule');
    $sponsored = new FreshRSS_Entry(title: 'Science news', authors: 'Sponsored Post');
    $result = filterEntry($sponsored);
    check($action === '1' ? $result === $sponsored && $sponsored->isRead() : $result === null,
        'Author rules use the shared reject-or-mark-as-read action');
    $ordinary = new FreshRSS_Entry(title: 'Science news', authors: 'Science Desk');
    check(filterEntry($ordinary) === $ordinary && !$ordinary->isRead(), 'Ordinary authors still pass');
}
echo "PASS author rules save, reload, render and apply both shared actions through the hook\n";

foreach ([
    ['contains', 'POST', 'Sponsored Post', true],
    ['equals', 'ÉCOLE STRASSE', 'École Straße', true],
    ['starts_with', 'sponsored', 'Sponsored Post', true],
    ['ends_with', 'post', 'Sponsored Post', true],
    ['contains', '/sponsored/', 'A /sponsored/ story', true],
    ['contains', '/sponsored/', 'Sponsored Post', false],
    ['equals', ' Sponsored Post ', 'Sponsored Post', false],
    ['equals', ' Sponsored Post ', ' Sponsored Post ', true],
    ['equals', '0', '0', true],
] as [$operator, $value, $author, $blocked]) {
    saveRules([array_replace($authorRule, ['operator' => $operator, 'value' => $value])]);
    $entry = new FreshRSS_Entry(title: 'Reporting');
    $entry->_authors([$author]);
    check((filterEntry($entry) === null) === $blocked, 'Literal ' . $operator . ': ' . $value);
}
foreach (['contains', 'equals', 'starts_with', 'ends_with'] as $operator) {
    saveRules([array_replace($authorRule, ['operator' => $operator, 'case_sensitive' => true])]);
    $entry = new FreshRSS_Entry(title: 'Reporting', authors: 'sponsored post');
    check(filterEntry($entry) === $entry, 'Case-sensitive literal matching preserves original case');
}
$before = file_get_contents(DATA_PATH . '/config.php');
foreach (['', " \t\n", "\u{00a0}"] as $blank) {
    $extension = submit(['blacklist' => '', 'whitelist' => '', 'rules_present' => '1',
        'rules' => [array_replace($authorRule, ['value' => $blank])]]);
    check($extension->getConfigurationErrors() !== [], 'Blank rule values are invalid');
    check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Blank values cannot overwrite working rules');
}
echo "PASS literal operators, Unicode case handling, literal regex-like text and non-blank values\n";

$entry = new FreshRSS_Entry(guid: 'coupon:123', title: 'A sponsored report', authors: 'Science Desk; Sponsored Post',
    link: 'https://www.macstories.net/sponsored/story?a=1&amp;b=2#detail', tags: ['News', 'Coupons']);
$entry->_attribute('thumbnail', ['url' => 'https://images.example.test/coupon-thumbnail.jpg?a=1&amp;b=2']);
foreach ([
    ['title', 'contains', 'sponsored'],
    ['authors', 'equals', 'Sponsored Post'],
    ['link', 'contains', '/sponsored/'],
    ['link', 'ends_with', '?a=1&b=2#detail'],
    ['tags', 'contains', 'coupon'],
    ['thumbnail_url', 'contains', 'coupon-thumbnail'],
    ['thumbnail_url', 'ends_with', '?a=1&b=2'],
    ['guid', 'equals', 'coupon:123'],
] as [$field, $operator, $value]) {
    saveRules([array_replace($authorRule, ['field' => $field, 'operator' => $operator, 'value' => $value])]);
    check(filterEntry($entry) === null, 'Match ' . $field . ' using its parsed entry source');
}
foreach ([['authors', 'Desk Sponsored'], ['tags', 'News Coupons']] as [$field, $value]) {
    saveRules([array_replace($authorRule, ['field' => $field, 'operator' => 'contains', 'value' => $value])]);
    check(filterEntry($entry) === $entry, 'Never join individual ' . $field . ' values');
}
$missing = new FreshRSS_Entry(guid: 'https://example.test/coupon', title: 'Ordinary',
    content: '<img src="https://example.test/coupon.jpg" />');
$missing->_attribute('enclosures', [['url' => 'https://example.test/coupon.jpg', 'type' => 'image/jpeg']]);
foreach (['authors', 'link', 'tags', 'thumbnail_url'] as $field) {
    saveRules([array_replace($authorRule, ['field' => $field, 'operator' => 'contains', 'value' => 'coupon'])]);
    check(filterEntry($missing) === $missing, 'Missing ' . $field . ' does not match or use fallback images/identifiers');
}
$encoded = new FreshRSS_Entry(title: 'Ordinary', link: 'https://example.test/%73ponsored/?keep=query#fragment');
saveRules([array_replace($authorRule, ['field' => 'link', 'operator' => 'contains', 'value' => '/sponsored/'])]);
check(filterEntry($encoded) === $encoded, 'Links are not URL-decoded');
$extension = saveRules([$authorRule], ['blacklist' => '', 'whitelist' => '/Science/']);
check(filterEntry(new FreshRSS_Entry(title: 'Science', authors: 'Sponsored Post')) === null,
    'A title whitelist cannot rescue a new blacklist match');
check(filterEntry(new FreshRSS_Entry(title: 'Politics', authors: 'Science Desk')) === null,
    'Legacy checks still run when new blacklist rules do not match');
echo "PASS all metadata fields, separate values, missing fields, raw URLs and legacy priority\n";

$parser = new FreshRSS_SimplePieCustom();
$parser->enable_cache(false);
$parser->set_raw_data(file_get_contents(__DIR__ . '/fixtures/metadata.xml'));
check($parser->init(), 'Parse the local feed fixture without fetching a feed');
$feed = new FreshRSS_Feed('https://example.test/feed', false);
$imported = iterator_to_array($feed->loadEntries($parser), false);
check(count($imported) === 1, 'Import exactly one fixture item');
foreach ([
    ['authors', 'equals', 'Sponsored Post'],
    ['tags', 'equals', 'Coupons'],
    ['tags', 'equals', 'Technology'],
    ['thumbnail_url', 'ends_with', 'coupon-thumbnail.jpg?a=1&b=2'],
] as [$field, $operator, $value]) {
    saveRules([array_replace($authorRule, ['field' => $field, 'operator' => $operator, 'value' => $value])]);
    check(filterEntry($imported[0]) === null, 'Real parser mapping for ' . $field . ': ' . $value);
}
echo "PASS local import maps CDATA creators, dc:subject, categories and explicit thumbnails\n";

saveRules([$authorRule]);
$before = file_get_contents(DATA_PATH . '/config.php');
$extension = submit(['blacklist' => '', 'whitelist' => '', 'rules_present' => '1',
    'rules' => [array_replace($authorRule, ['field' => 'content'])]]);
check($extension->getConfigurationErrors() !== [], 'Content matching is not supported in this release');
check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Rejected content rules leave saved settings unchanged');
check(!str_contains(extension()->getConfigureView(), 'Content text'), 'Do not offer content matching in the editor');
echo "PASS content matching is unavailable\n";

foreach ([
    ['/sponsored post/i', true, true], ['/sponsored post/', false, false],
    ['~^Sponsored Post$~', false, true], ['{^Sponsored Post$}', true, true],
    ['(Sponsored Post)', false, true], ['*Sponsored Post*', false, true],
    ['=Sponsored Post=', false, true], ['#(*UTF)École#i', true, true],
    ['~Sponsored\\~Post~', false, true], ['#Sponsored~Post#', false, true],
    ['#\\QSponsored~Post\\E#', false, true], ['=\\QSponsored~Post\\E=', false, true],
] as [$pattern, $caseSensitive, $blocked]) {
    saveRules([array_replace($authorRule, ['operator' => 'regex', 'value' => $pattern, 'case_sensitive' => $caseSensitive])]);
    $author = str_contains($pattern, 'École') ? 'éCOLE' : (str_contains($pattern, '~Post') ? 'Sponsored~Post' : 'Sponsored Post');
    $entry = new FreshRSS_Entry(title: 'Reporting', authors: $author);
    check((filterEntry($entry) === null) === $blocked, 'Regex flags alone control matching: ' . $pattern);
}
$before = file_get_contents(DATA_PATH . '/config.php');
foreach (['/unclosed', 'not a regex', '~[~', '/good/z'] as $invalid) {
    $extension = submit(['blacklist' => '/changed/', 'whitelist' => '/changed/', 'mark_as_read' => '1',
        'rules_present' => '1', 'rules' => [array_replace($authorRule, ['operator' => 'regex', 'value' => $invalid])]]);
    check($extension->getConfigurationErrors() !== [], 'Invalid regex never becomes a literal: ' . $invalid);
    check(str_contains($extension->getConfigureView(), 'row 1: invalid regular expression'), 'Identify the invalid regex row');
    check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Invalid new regex leaves all previous settings intact');
}
echo "PASS complete regex syntax, flags, unusual delimiters, validation and no literal fallback\n";

$expensive = array_replace($authorRule, ['operator' => 'regex', 'value' => '~(*NO_JIT)(a+)+$~']);
$limits = [ini_get('pcre.backtrack_limit'), ini_get('pcre.recursion_limit')];
$extension = saveRules([$expensive]);
$logPath = USERS_PATH . '/_/' . LOG_FILENAME;
$beforeLog = file_get_contents($logPath);
$pathological = new FreshRSS_Entry(title: 'Ordinary', authors: str_repeat('a', 20000) . '!');
check(filterEntry($pathological) === $pathological, 'A regex execution failure does not reject an entry');
$firstLog = file_get_contents($logPath);
$newLog = substr($firstLog, strlen($beforeLog));
check(str_contains($newLog, 'rule 1 (authors)') && str_contains($newLog, 'Backtrack limit'),
    'Regex failure logs the rule row, field and actionable PCRE error');
check(!str_contains($newLog, str_repeat('a', 100)), 'Never log full matched field values');
check(filterEntry($pathological) === $pathological, 'Failed regex remains non-blocking on later items');
check(file_get_contents($logPath) === $firstLog, 'Repeated runtime failures do not flood the log');
check([ini_get('pcre.backtrack_limit'), ini_get('pcre.recursion_limit')] === $limits,
    'Regex limits remain local to each pattern');
saveRules([$expensive, $authorRule]);
$pathological->_authors([str_repeat('a', 20000) . '!', 'Sponsored Post']);
check(filterEntry($pathological) === null, 'Continue to other new rules after a failed regex');
saveRules([$expensive], ['blacklist' => 'Ordinary']);
check(filterEntry($pathological) === null, 'Continue to legacy blacklist after a failed regex');
saveRules([$expensive], ['whitelist' => 'Science']);
check(filterEntry($pathological) === null, 'Continue to legacy whitelist after a failed regex');
$recursive = array_replace($authorRule, ['operator' => 'regex', 'value' => '~(*NO_JIT)(a(?1)?a)$~']);
saveRules([$recursive]);
$deep = new FreshRSS_Entry(title: 'Ordinary', authors: str_repeat('a', 5000));
check(filterEntry($deep) === $deep, 'A depth-limit failure does not reject an entry');
check(str_contains(file_get_contents($logPath), 'Recursion limit'), 'Depth failures are reported');
echo "PASS regex execution bounds, useful deduplicated logs and continued filtering\n";

// FreshRSS lowers PCRE defaults during parser bootstrap; simulate a host with higher limits too.
$originalLimits = [ini_get('pcre.backtrack_limit'), ini_get('pcre.recursion_limit')];
ini_set('pcre.backtrack_limit', '1000000');
ini_set('pcre.recursion_limit', '100000');
foreach ([
    ['~(*LIMIT_MATCH=900000)(*NO_JIT)(a+)+$~', str_repeat('a', 16) . '!', 'Backtrack limit'],
    ['~(*LIMIT_DEPTH=900000)(*NO_JIT)(a(?1)?a)$~', str_repeat('a', 1100), 'Recursion limit'],
    ['~(*LIMIT_MATCH=10)(*NO_JIT)(a+)+$~', 'aaaaa!', 'Backtrack limit'],
] as [$pattern, $author, $error]) {
    saveRules([array_replace($authorRule, ['operator' => 'regex', 'value' => $pattern])]);
    $beforeLog = file_get_contents($logPath);
    $entry = new FreshRSS_Entry(title: 'Reporting', authors: $author);
    check(filterEntry($entry) === $entry, 'Supplied directives cannot raise local regex limits');
    check(str_contains(substr(file_get_contents($logPath), strlen($beforeLog)), $error),
        'Enforce local caps while retaining lower supplied limits');
}
saveRules([array_replace($authorRule, ['operator' => 'regex', 'value' => '~\\Q(*LIMIT_MATCH=900000)\\E~'])]);
check(filterEntry(new FreshRSS_Entry(title: 'Reporting', authors: '(*LIMIT_MATCH=900000)')) === null,
    'Limit-like text inside regex quoting remains literal');
check(ini_get('pcre.backtrack_limit') === '1000000' && ini_get('pcre.recursion_limit') === '100000',
    'Filtering does not change the simulated host limits');
ini_set('pcre.backtrack_limit', $originalLimits[0]);
ini_set('pcre.recursion_limit', $originalLimits[1]);
echo "PASS supplied regex directives cannot bypass local execution limits\n";

$injected = '\"><script>alert(1)</script>&';
$extension = submit(['blacklist' => 'keep editable', 'whitelist' => '/good/', 'mark_as_read' => '1',
    'rules_present' => '1', 'rules' => [array_replace($authorRule, ['field' => $injected, 'value' => $injected])]]);
$view = $extension->getConfigureView();
check(str_contains($view, htmlspecialchars($injected, ENT_QUOTES, 'UTF-8')), 'Keep invalid rule values escaped for correction');
check(!str_contains($view, '<script>'), 'Invalid rule input must not inject markup');
check(str_contains($view, 'row 1:'), 'Tie invalid structured rules to their row');
check(str_contains($view, 'keep editable'), 'Keep legacy controls in the same failed-save form');
check((bool)preg_match('/id="mark_as_read"[^>]*checked/', $view), 'Keep global action after an invalid structured rule');
echo "PASS invalid structured rules retain escaped editable input and shared controls\n";

saveRules([$authorRule], ['blacklist' => 'Legacy', 'whitelist' => '', 'mark_as_read' => '1']);
$before = file_get_contents(DATA_PATH . '/config.php');
foreach ([
    null, 'not a list', [null], ['named' => $authorRule],
    [array_replace($authorRule, ['field' => 'unknown'])],
    [array_replace($authorRule, ['operator' => 'unknown'])],
    [array_replace($authorRule, ['value' => ['nested' => $injected]])],
    [array_replace($authorRule, ['field' => [$injected]])],
    [array_replace($authorRule, ['operator' => [$injected]])],
    [array_replace($authorRule, ['case_sensitive' => 'yes'])],
    [array_replace($authorRule, ['case_sensitive' => null])],
    [array_replace($authorRule, ['unexpected' => 'extra'])],
] as $malformed) {
    $extension = submit(['blacklist' => 'changed', 'whitelist' => 'changed', 'mark_as_read' => '',
        'rules_present' => '1', 'rules' => $malformed]);
    check($extension->getConfigurationErrors() !== [], 'Reject malformed rule lists and record shapes');
    check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Malformed rules never partially save any settings');
    check(!str_contains($extension->getConfigureView(), '<script>'), 'Malformed nested input redisplays without warnings or injection');
}
foreach ([['rules_present' => ['1']], ['rules_present' => '0'], ['rules' => []]] as $marker) {
    $extension = submit($marker + ['blacklist' => 'changed', 'whitelist' => '', 'mark_as_read' => '']);
    check($extension->getConfigurationErrors() !== [], 'Reject malformed or missing editor markers when editor data is supplied');
    check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Invalid editor markers cannot erase rules');
}
submit(['blacklist' => 'Updated legacy', 'whitelist' => '', 'mark_as_read' => '']);
check(extension()->getSystemConfigurationValue('rules') === [$authorRule], 'An older form must preserve structured rules');
$extension = submit(['blacklist' => '', 'whitelist' => '', 'mark_as_read' => '', 'rules_present' => '1',
    'rules' => [['field' => 'authors', 'operator' => 'equals', 'value' => 'Sponsored Post']]]);
check($extension->getConfigurationErrors() === [], 'An unchecked literal case checkbox defaults to false');
check(extension()->getSystemConfigurationValue('rules') === [$authorRule], 'Store canonical boolean case sensitivity');
submit(['blacklist' => '', 'whitelist' => '', 'mark_as_read' => '', 'rules_present' => '1']);
check(extension()->getSystemConfigurationValue('rules') === [], 'An explicit empty editor removes every structured rule');
$before = file_get_contents(DATA_PATH . '/config.php');
$_SERVER['REQUEST_METHOD'] = 'GET';
Minz_Request::_params(['rules_present' => '1', 'rules' => [$authorRule]]);
extension()->handleConfigureAction();
check(file_get_contents(DATA_PATH . '/config.php') === $before, 'GET cannot change structured rules');
echo "PASS malformed submissions, canonical storage, stale forms, explicit removal and GET protection\n";

foreach ([null, 'broken', [$authorRule, ['field' => 'unknown']],
    [array_replace($authorRule, ['case_sensitive' => '1'])],
    [array_replace($authorRule, ['operator' => 'regex', 'value' => '/broken[/'])]] as $malformed) {
    $settings = FreshRSS_Context::systemConf()->extensions;
    $settings['FilterTitle'] = ['blacklist' => ['Legacy'], 'whitelist' => [], 'mark_as_read' => '', 'rules' => $malformed];
    FreshRSS_Context::systemConf()->extensions = $settings;
    $extension = activate();
    $ordinary = new FreshRSS_Entry(title: 'Ordinary', authors: 'Sponsored Post');
    check(filterEntry($ordinary) === $ordinary, 'A malformed stored list skips every new rule, including valid rows');
    check(filterEntry(new FreshRSS_Entry(title: 'Legacy')) === null, 'Malformed stored rules leave legacy checks active');
    check(str_contains($extension->getConfigureView(), 'Saved additional blacklist rules are invalid'),
        'Explain malformed stored settings on the configuration page');
    check(str_contains(file_get_contents($logPath), 'Saved additional blacklist rules are invalid'),
        'Log a useful saved-configuration error');
}
echo "PASS malformed stored lists are reported and skipped without breaking legacy filtering\n";

$extension = saveRules([$authorRule, array_replace($authorRule, ['field' => 'tags', 'value' => 'Promoted'])]);
$_SERVER['REQUEST_METHOD'] = 'GET';
Minz_Request::_params([]);
$extension->handleConfigureAction();
$view = $extension->getConfigureView();
$document = new DOMDocument();
$document->loadHTML($view, LIBXML_NOERROR | LIBXML_NOWARNING);
$xpath = new DOMXPath($document);
check($xpath->query('//form')->length === 1 && $xpath->query('//input[@name="_csrf"]')->length === 1,
    'Keep the single existing POST/CSRF form');
check($xpath->query('//input[@name="rules_present" and @value="1"]')->length === 1, 'Identify explicit empty-editor submissions');
check($xpath->query('//button[@type="submit"]')->item(0)->getAttribute('name') !== 'rules_action',
    'Pressing Enter must save, not trigger the first remove-row button');
check($document->getElementById('filter-title-add-rule') !== null, 'Render the add-rule button');
check($document->getElementById('filter-title-rule-template') !== null, 'Render a reusable row template');
check($xpath->query('//div[@id="filter-title-rules"]//button[@data-remove-rule]')->length === 2, 'Render remove controls for every saved row');
foreach ($xpath->query('//div[@id="filter-title-rules"]//select | //div[@id="filter-title-rules"]//input') as $control) {
    $id = $control->getAttribute('id');
    check($id !== '' && $xpath->query('//label[@for="' . $id . '"]')->length === 1,
        'Each row control has a unique associated label');
}
check(str_contains(Minz_View::headScript(), 'configure.js') && !str_contains(Minz_View::headScript(), 'async='),
    'Load the small editor script through FreshRSS asset management without async ordering races');
echo "PASS labelled repeatable rows, shared form, CSRF and supported assets\n";

saveRules([$authorRule]);
$before = file_get_contents(DATA_PATH . '/config.php');
$extension = submit(['blacklist' => '/unfinished', 'whitelist' => '', 'mark_as_read' => '1',
    'rules_present' => '1', 'rules' => [$authorRule], 'rules_action' => 'add']);
check($extension->getConfigurationErrors() === [], 'Adding a row without JavaScript is not a save');
$view = $extension->getConfigureView();
check(str_contains($view, 'name="rules[1][field]"'), 'Server fallback appends an editable row');
check(str_contains($view, '/unfinished'), 'Server fallback keeps unsaved title input');
check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Adding a row never persists incomplete settings');
$extension = submit(['blacklist' => 'unsaved', 'whitelist' => '', 'rules_present' => '1',
    'rules' => [$authorRule], 'rules_action' => 'remove_0']);
check($extension->getConfigurationErrors() === [] && $extension->getRulesData() === [], 'Server fallback removes a row');
check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Removing a row waits for the shared Save action');
echo "PASS row editing works without a loaded script and never saves until requested\n";

$before = file_get_contents(DATA_PATH . '/config.php');
foreach (glob(dirname(__DIR__) . '/i18n/*', GLOB_ONLYDIR) as $language) {
    Minz_Translate::reset(basename($language));
    $extension = submit(['blacklist' => '', 'whitelist' => '', 'mark_as_read' => '', 'rules_present' => '1',
        'rules' => [array_replace($authorRule, ['operator' => 'regex', 'value' => '/broken[/'])]]);
    $view = $extension->getConfigureView();
    foreach (['Additional blacklist rules', 'row 1: invalid regular expression', 'Thumbnail URL', 'Starts with', 'Add rule'] as $text) {
        check(str_contains($view, $text), 'New labels and errors fall back to English in ' . basename($language));
    }
    check(!str_contains($view, 'ext.filter_title.'), 'No untranslated keys leak into the settings form');
    check(file_get_contents(DATA_PATH . '/config.php') === $before, 'Locale fallback does not change save validation');
}
Minz_Translate::reset('en');
echo "PASS English fallback for every new label and row error in every shipped locale\n";
