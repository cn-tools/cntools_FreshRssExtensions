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

function submit(array $params): FilterTitleExtension {
    $_SERVER['REQUEST_METHOD'] = 'POST';
    Minz_Request::_params($params);
    $extension = extension();
    $extension->handleConfigureAction();
    return $extension;
}

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
check($extension->filterTitle($ordinary) === $ordinary, 'Ordinary Wired reporting must still enter the database');
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
