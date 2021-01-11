<?php
/*
from: https://www.youtube.com/channel/UCLRLC0yPbRD-JaKzb5wKDdQ
to: https://www.youtube.com/feeds/videos.xml?channel_id=UCLRLC0yPbRD-JaKzb5wKDdQ

from: https://www.youtube.com/user/abcdefghijklmnop
to: https://www.youtube.com/feeds/videos.xml?user=abcdefghijklmnop

from: https://www.youtube.com/playlist?list=PLcZ1vtdmuI2P7eZvbSq_d5mJXXywHtfyq
to: https://www.youtube.com/feeds/videos.xml?playlist_id=PLcZ1vtdmuI2P7eZvbSq_d5mJXXywHtfyq

*/
class YouTubeChannel2RssFeedExtension extends Minz_Extension {
    const CNT_REQUIRDED_FRESHRSS_VERSION = '1.16';

	public function init() {
		$this->registerHook('check_url_before_add', array('YouTubeChannel2RssFeedExtension', 'CntYTRssHookCheckURL'));
	}

	public function install() {
        if (version_compare(FRESHRSS_VERSION, self::CNT_REQUIRDED_FRESHRSS_VERSION , '<')){
            $this->registerTranslates();
            return _t('ext.YouTubeChannel2RssFeed.install.bad_freshrss', self::CNT_REQUIRDED_FRESHRSS_VERSION, FRESHRSS_VERSION);
        }
		return true;
	}

	public static function CntYTRssHookCheckURL($url) {
        $matches = [];

        if (preg_match('#^https?://(www\.|)youtube\.com/channel/([0-9a-zA-Z_-]{6,36})#', $url, $matches) === 1) {
            return 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $matches[2];
        }

        if (preg_match('#^https?://(www\.|)youtube\.com/user/([0-9a-zA-Z_-]{6,36})#', $url, $matches) === 1) {
            return 'https://www.youtube.com/feeds/videos.xml?user=' . $matches[2];
        }

        if (preg_match('#^https?://(www\.|)youtube\.com/playlist/?\?list=([0-9a-zA-Z_-]{6,36})#', $url, $matches) === 1) {
            return 'https://www.youtube.com/feeds/videos.xml?playlist_id=' . $matches[2];
        }
        return $url;
	}
}
