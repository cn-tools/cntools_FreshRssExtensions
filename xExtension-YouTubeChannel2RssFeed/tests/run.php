<?php
declare(strict_types=1);

class Minz_Extension {}
class Minz_Log {
    public static function debug(string $message): void {}
    public static function error(string $message): void {}
}
class FreshRSS_Context { public static object $user_conf; }

final class MockYouTubeOperationalApi {
    public mixed $context;
    public static string $requestedUrl = '';
    private string $content = '{"items":[{"id":"UClvRFciRu04eKgti3g7qHUA"}]}';
    private int $position = 0;

    public function stream_open(string $path, string $mode, int $options, ?string &$opened_path): bool {
        self::$requestedUrl = $path;
        return str_starts_with($mode, 'r');
    }
    public function stream_read(int $count): string {
        $chunk = substr($this->content, $this->position, $count);
        $this->position += strlen($chunk);
        return $chunk;
    }
    public function stream_eof(): bool { return $this->position >= strlen($this->content); }
    public function stream_stat(): array { return []; }
    public function url_stat(string $path, int $flags): array { return []; }
}
stream_wrapper_register('mockyt', MockYouTubeOperationalApi::class);
require __DIR__ . '/../extension.php';

function expectUrl(YouTubeChannel2RssFeedExtension $extension, string $input, string $expected, string $label): void {
    if ($extension->CntYTRssHookCheckURL($input) !== $expected) {
        fwrite(STDERR, "FAIL {$label}\n");
        exit(1);
    }
    fwrite(STDOUT, "PASS {$label}\n");
}

$extension = new YouTubeChannel2RssFeedExtension();
$channel = 'UClvRFciRu04eKgti3g7qHUA';
$channelUrl = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $channel;
$longFormUrl = 'https://www.youtube.com/feeds/videos.xml?playlist_id=UULF' . substr($channel, 2);
$direct = 'https://www.youtube.com/channel/' . $channel;
$handle = 'https://www.youtube.com/@example';
FreshRSS_Context::$user_conf = (object)['YouTubeChannel2RssFeed' => ['3rd_party_url' => 'mockyt://api']];
expectUrl($extension, $direct, $channelUrl, 'unset option preserves channel feed');
expectUrl($extension, $handle, $channelUrl, 'unset option preserves handle feed');
FreshRSS_Context::$user_conf->YouTubeChannel2RssFeed['long_form'] = '0';
expectUrl($extension, $direct, $channelUrl, 'disabled option preserves channel feed');
FreshRSS_Context::$user_conf->YouTubeChannel2RssFeed['long_form'] = '1';
expectUrl($extension, $direct, $longFormUrl, 'enabled channel uses UULF uploads playlist');
expectUrl($extension, $handle, $longFormUrl, 'enabled resolved handle uses UULF uploads playlist');
if (MockYouTubeOperationalApi::$requestedUrl !== 'mockyt://api/channels?handle=@example') exit(2);
expectUrl($extension, 'https://www.youtube.com/channel/UCinvalid', 'https://www.youtube.com/feeds/videos.xml?channel_id=UCinvalid', 'malformed channel ID does not generate UULF');
expectUrl($extension, 'https://www.youtube.com/user/YouTube', 'https://www.youtube.com/feeds/videos.xml?user=YouTube', 'legacy user URL unchanged');
$playlist = 'PLcZ1vtdmuI2P7eZvbSq_d5mJXXywHtfyq';
expectUrl($extension, 'https://www.youtube.com/playlist?list=' . $playlist, 'https://www.youtube.com/feeds/videos.xml?playlist_id=' . $playlist, 'explicit playlist unchanged');
expectUrl($extension, $channelUrl, $channelUrl, 'existing channel feed URL unchanged');
expectUrl($extension, $longFormUrl, $longFormUrl, 'existing UULF feed URL unchanged');
