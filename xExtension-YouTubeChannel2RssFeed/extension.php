<?php
/*
from: https://www.youtube.com/channel/UCLRLC0yPbRD-JaKzb5wKDdQ
to: https://www.youtube.com/feeds/videos.xml?channel_id=UCLRLC0yPbRD-JaKzb5wKDdQ
*/
class YouTubeChannel2RssFeedExtension extends Minz_Extension {
	const CNT_YT_SEARCHSTR = 'youtube.com/channel/';
	const CNT_YT_NEWSTR = 'youtube.com/feed/videos.xml?channel_id=';

	public function init() {
		$this->registerHook('check_url_before_add', array('YouTubeChannel2RssFeedExtension', 'CntYTRssHookCheckURL'));
	}

	public static function CntYTRssHookCheckURL($url) {
		return str_replace(CNT_YT_SEARCHSTR, CNT_YT_NEWSTR, $url);
	}
}
