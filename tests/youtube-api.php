<?php

// Real HTTP requests, with the same stream restrictions as FreshRSS 1.30.
// Only FreshRSS's surrounding classes are stubbed; extension and cURL are real.
class Minz_Extension {}
class Minz_Log {
    public static $errors = [];
    public static function debug($message) {}
    public static function notice($message) {}
    public static function error($message) { self::$errors[] = $message; }
}
class FreshRSS_Context { public static $user_conf; }
class TestEntry {
    public $read = false;
    private $id;
    public function __construct($id) { $this->id = $id; }
    public function guid() { return 'yt:video:' . $this->id; }
    public function _isread($read) { $this->read = $read; }
}

$baseUrl = $argv[1] ?? 'http://127.0.0.1:9091';
$extensionFile = getenv('YOUTUBE_EXTENSION_FILE') ?: __DIR__ . '/../xExtension-YouTubeChannel2RssFeed/extension.php';
require $extensionFile;
$originalAllowUrlFopen = ini_get('allow_url_fopen');
foreach (stream_get_wrappers() as $protocol) {
    if (!in_array($protocol, ['file', 'php'], true)) stream_wrapper_unregister($protocol);
}
$extension = new YouTubeChannel2RssFeedExtension();
$failures = 0;
function check($condition, $name) {
    global $failures;
    if (!$condition) $failures++;
    echo ($condition ? 'PASS ' : 'FAIL ') . $name . "\n";
}
function configure($url, $mode = 'mark_as_read') {
    FreshRSS_Context::$user_conf = (object)['YouTubeChannel2RssFeed' => ['3rd_party_url' => $url, 'shorts' => $mode, 'short_duration' => 120]];
    Minz_Log::$errors = [];
}

configure($baseUrl);
$entry = new TestEntry('0Dqo8CHNPuc');
check($extension->CntYTRssHookEntryBeforeInsert($entry) === $entry && $entry->read, 'Short marked read without HTTP stream wrappers');
$entry = new TestEntry('hoS-Bvxo09k');
check($extension->CntYTRssHookEntryBeforeInsert($entry) === $entry && !$entry->read, 'Regular video remains unread');
$entry = new TestEntry('duration001');
check($extension->CntYTRssHookEntryBeforeInsert($entry) === $entry && $entry->read, 'Duration threshold still marks short videos read');
check($extension->CntYTRssHookCheckURL('https://www.youtube.com/@test') === 'https://www.youtube.com/feeds/videos.xml?channel_id=UCbXcGh0FCyhK09m0Qj9pQRg', 'Handle resolved without HTTP stream wrappers');
check(Minz_Log::$errors === [], 'Successful requests do not log errors');

configure($baseUrl, 'block');
check($extension->CntYTRssHookEntryBeforeInsert(new TestEntry('0Dqo8CHNPuc')) === null, 'Block mode drops Short');
configure($baseUrl, '0');
$entry = new TestEntry('0Dqo8CHNPuc');
check($extension->CntYTRssHookEntryBeforeInsert($entry) === $entry && !$entry->read, 'Disabled filtering preserves entry');

configure($baseUrl . '/redirect');
$entry = new TestEntry('0Dqo8CHNPuc');
check($extension->CntYTRssHookEntryBeforeInsert($entry) === $entry && $entry->read, 'HTTP redirect followed');

configure($baseUrl . '/error');
$entry = new TestEntry('0Dqo8CHNPuc');
check($extension->CntYTRssHookEntryBeforeInsert($entry) === $entry && !$entry->read && count(Minz_Log::$errors) === 1 && strpos(Minz_Log::$errors[0], '503') !== false, 'HTTP failure preserves entry and logs error');
configure($baseUrl . '/error');
$url = 'https://www.youtube.com/@test';
check($extension->CntYTRssHookCheckURL($url) === $url && count(Minz_Log::$errors) === 1, 'HTTP failure preserves original channel URL');
configure('file:///not-an-api');
check($extension->CntYTRssHookCheckURL($url) === $url && count(Minz_Log::$errors) === 1, 'Non-HTTP API URL rejected');

check(!in_array('http', stream_get_wrappers(), true) && !in_array('https', stream_get_wrappers(), true), 'HTTP(S) stream wrappers remain disabled');
check(ini_get('allow_url_fopen') === $originalAllowUrlFopen, 'allow_url_fopen unchanged');
exit($failures ? 1 : 0);
