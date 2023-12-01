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
	public static $channelID = '';

	public function init() {
		self::$channelID = '';
		$this->registerHook('check_url_before_add', array('YouTubeChannel2RssFeedExtension', 'CntYTRssHookCheckURL'));
		$this->registerHook('feed_before_insert', array('YouTubeChannel2RssFeedExtension', 'CntYTRssHookBeforeInsertFeed'));
	}

	public function install() {
        if (version_compare(FRESHRSS_VERSION, self::CNT_REQUIRDED_FRESHRSS_VERSION , '<')){
            $this->registerTranslates();
            return _t('ext.YouTubeChannel2RssFeed.install.bad_freshrss', self::CNT_REQUIRDED_FRESHRSS_VERSION, FRESHRSS_VERSION);
        }
		return true;
	}

	public static function CntYTRssHookBeforeInsertFeed($feed) {
		if (self::$channelID != '') {
			$lTxt = $feed->name() ? $feed->name() : 'YouTubeChannel2Rss by CN-Tools';
			$url = 'https://www.scriptbarrel.com/xml.cgi?channel_id=' . self::$channelID . '&name=' . urlencode($lTxt);
			$feed->_url($url);
		}
		return $feed;
	}
	
	public static function CntYTRssHookCheckURL($url) {
        $matches = [];
        if (preg_match('#^https?://(www\.|)youtube\.com/channel/(@[0-9a-zA-Z_-])#', $url, $matches) === 1) {
                ini_set("allow_url_fopen",1);
                $json = file_get_contents('https://yt.lemnoslife.com/channels?handle=' . $matches[2]);
                $data = json_decode($json);
                $id= $data->items[0]->id;
                self::$channelID = $id;
            return 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $id;
        }
        if (preg_match('#^https?://(www\.|)youtube\.com/channel/([0-9a-zA-Z_-]{6,36})#', $url, $matches) === 1) {
			self::$channelID = $matches[2];
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
