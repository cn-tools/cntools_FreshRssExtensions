<?php
/*
from: https://www.youtube.com/channel/UCLRLC0yPbRD-JaKzb5wKDdQ
to: https://www.youtube.com/feeds/videos.xml?channel_id=UCLRLC0yPbRD-JaKzb5wKDdQ

from: https://www.youtube.com/user/abcdefghijklmnop
to: https://www.youtube.com/feeds/videos.xml?user=abcdefghijklmnop

from: https://www.youtube.com/playlist?list=PLcZ1vtdmuI2P7eZvbSq_d5mJXXywHtfyq
to: https://www.youtube.com/feeds/videos.xml?playlist_id=PLcZ1vtdmuI2P7eZvbSq_d5mJXXywHtfyq

with third party url:
from: https://www.youtube.com/@youtube
to: https://www.youtube.com/feeds/videos.xml?channel_id=UCBR8-60-B28hp2BmDPdntcQ
*/

class YouTubeChannel2RssFeedExtension extends Minz_Extension {
    const CNT_REQUIRDED_FRESHRSS_VERSION = '1.16';
    const CNT_YT_VIDEO = 'yt:video:';
    const CNT_YT_FEEDURL = 'youtube.com/feeds/videos.xml?';

    public function init() {
        $this->registerHook('check_url_before_add', [$this, 'CntYTRssHookCheckURL']);
        $this->registerHook('entry_before_insert', [$this, 'CntYTRssHookEntryBeforeInsert']);
    }

    public function install() {
        if (version_compare(strval(FRESHRSS_VERSION), strval(self::CNT_REQUIRDED_FRESHRSS_VERSION), '<')){
            $this->registerTranslates();
            return _t('ext.YouTubeChannel2RssFeed.install.bad_freshrss', self::CNT_REQUIRDED_FRESHRSS_VERSION, FRESHRSS_VERSION);
        }
        return true;
    }

    public function handleConfigureAction() {
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            $config = [
                '3rd_party_url' => rtrim(Minz_Request::paramString('3rd_party_url', ''), '/'),
                'shorts' => Minz_Request::paramString('shorts', ''),
                'short_duration' => Minz_Request::paramString('short_duration', 0),
            ];

            FreshRSS_Context::$user_conf->YouTubeChannel2RssFeed = $config;
            FreshRSS_Context::$user_conf->save();
        }
    }

    private function configKeyExists($key):bool {
        Minz_Log::debug('YouTubeChannel2RssFeed-configKeyExists: START');
        $result = false;
        if ($key != '') {
            Minz_Log::debug('YouTubeChannel2RssFeed-configKeyExists: key=' . $key);
            if (null !== FreshRSS_Context::$user_conf) {
                Minz_Log::debug('YouTubeChannel2RssFeed-configKeyExists: user_conf available');
                if (null !== FreshRSS_Context::$user_conf->YouTubeChannel2RssFeed) {
                    Minz_Log::debug('YouTubeChannel2RssFeed-configKeyExists: YouTubeChannel2RssFeed available');
                    if (is_array(FreshRSS_Context::$user_conf->YouTubeChannel2RssFeed) ) {
                        Minz_Log::debug('YouTubeChannel2RssFeed-configKeyExists: YouTubeChannel2RssFeed is a array');
                        if (array_key_exists($key, FreshRSS_Context::$user_conf->YouTubeChannel2RssFeed)) {
                            Minz_Log::debug('YouTubeChannel2RssFeed-configKeyExists: key in array found');
                            $result = true;
                        }
                    }
                }
            }
        }

        Minz_Log::debug('YouTubeChannel2RssFeed-configKeyExists: END result=' . ($result ? 'found' : 'not found'));
        return $result;
    }

    public function getConfigValueByKeyWithDef($key, $def) {
        Minz_Log::debug('YouTubeChannel2RssFeed-getConfigValueByKeyAsStr: START');
        $value = $def;
        if ($this->configKeyExists($key) == true) {
            $value = FreshRSS_Context::$user_conf->YouTubeChannel2RssFeed[$key];
        }

        Minz_Log::debug('YouTubeChannel2RssFeed-getConfigValueByKeyAsStr: value=' . var_export($value, true));
        return $value;
    }

    public function getThirdPartyUrl():string {
        Minz_Log::debug('YouTubeChannel2RssFeed-getThirdPartyUrl: START');
        return $this->getConfigValueByKeyWithDef('3rd_party_url', '');
    }

    public function getShortDuration():int {
        Minz_Log::debug('YouTubeChannel2RssFeed-getShortDuration: START');
        return intval($this->getConfigValueByKeyWithDef('short_duration', 0));
    }

    public function CntYTRssHookCheckURL($url) {
        Minz_Log::debug('YouTubeChannel2RssFeed-CntYTRssHookCheckURL: START - ' . $url);
        if (stripos($url, self::CNT_YT_FEEDURL) === false) {
            $origUrl = $url;
            try {
                $matches = [];

                if (preg_match('#^https?://(www\.|)youtube\.com/channel/([0-9a-zA-Z_-]{6,36})#', $url, $matches) === 1) {
                    Minz_Log::debug('YouTubeChannel2RssFeed-CntYTRssHookCheckURL: channel=' . $matches[2]);
                    return 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $matches[2];
                }

                if (preg_match('#^https?://(www\.|)youtube\.com/user/([0-9a-zA-Z_-]{6,36})#', $url, $matches) === 1) {
                    Minz_Log::debug('YouTubeChannel2RssFeed-CntYTRssHookCheckURL: user=' . $matches[2]);
                    return 'https://www.youtube.com/feeds/videos.xml?user=' . $matches[2];
                }

                if (preg_match('#^https?://(www\.|)youtube\.com/playlist/?\?list=([0-9a-zA-Z_-]{6,36})#', $url, $matches) === 1) {
                    Minz_Log::debug('YouTubeChannel2RssFeed-CntYTRssHookCheckURL: playlist=' . $matches[2]);
                    return 'https://www.youtube.com/feeds/videos.xml?playlist_id=' . $matches[2];
                }

                $extUrl = $this->getThirdPartyUrl();
                if ($extUrl != ''){
                    if (preg_match('#^https?://(www\.|)youtube\.com/([\@0-9a-zA-Z_-]{1,60})#', $url, $matches) === 1) {
                        $origAllowUrlFopen = ini_get('allow_url_fopen');

                        try {
                            Minz_Log::debug('YouTubeChannel2RssFeed-CntYTRssHookCheckURL: 3rdParty - start');
                            ini_set("allow_url_fopen", 1);
                            $json = file_get_contents($extUrl . '/channels?handle=' . $matches[2]);
                            Minz_Log::debug('YouTubeChannel2RssFeed-CntYTRssHookCheckURL: 3rdParty - json=' . serialize($json));
                        } finally {
                            ini_set("allow_url_fopen", $origAllowUrlFopen);
                            Minz_Log::debug('YouTubeChannel2RssFeed-CntYTRssHookCheckURL: 3rdParty - end');
                        }

                        $data = json_decode($json);
                        $id = strval($data->items[0]->id);
                        Minz_Log::debug('YouTubeChannel2RssFeed-CntYTRssHookCheckURL: 3rdParty=' . $matches[2]);
                        return 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $id;
                    }
                }
            } catch (Exception $e) {
                $url = $origUrl;
                Minz_Log::error('YouTubeChannel2RssFeed-CheckURL: ' . $e->getMessage());
            }
        }

        return $url;
    }

    public function CntYTRssHookEntryBeforeInsert($entry) {
        Minz_Log::debug('YouTubeChannel2RssFeed-EntryBeforeInsert - START');
        try {
            if ((is_object($entry)) && (strpos($entry->guid(), self::CNT_YT_VIDEO) !== false)) {
                Minz_Log::debug('YouTubeChannel2RssFeed-EntryBeforeInsert - isYT: ' . serialize($entry));
                $extUrl = $this->getThirdPartyUrl();
                if ($extUrl != '') {
                    $shorts = strtolower(strval(FreshRSS_Context::$user_conf->YouTubeChannel2RssFeed["shorts"]));
                    Minz_Log::debug('YouTubeChannel2RssFeed-EntryBeforeInsert: short-content = ' . $shorts);

                    if ($shorts != '0') {
                        $ytID = substr($entry->guid(), strlen(self::CNT_YT_VIDEO));
                        Minz_Log::debug('YouTubeChannel2RssFeed-EntryBeforeInsert: before check isShort (id=' . $ytID . ')');
                        if (self::isShort($entry, $extUrl, $ytID) == true) {
                            switch ($shorts) {
                                case "block":
                                    Minz_Log::debug('YouTubeChannel2RssFeed-EntryBeforeInsert: block short');
                                    Minz_Log::notice('YouTubeChannel2RssFeed: short blocked (id=' . $ytID . ')');
                                    return null;
                                    break;
                                case "mark_as_read":
                                    Minz_Log::debug('YouTubeChannel2RssFeed-EntryBeforeInsert: mark short as read');
                                    Minz_Log::notice('YouTubeChannel2RssFeed: mark short as read (id=' . $ytID . ')');
                                    $entry->_isread(true);
                                    return $entry;
                                    break;
                                default:
                                    Minz_Log::debug('YouTubeChannel2RssFeed-EntryBeforeInsert: do nothing');
                                    return $entry;
                            }
                        } else {
                            Minz_Log::debug('YouTubeChannel2RssFeed-EntryBeforeInsert: is not short');
                        }
                    } else {
                        Minz_Log::debug('YouTubeChannel2RssFeed-EntryBeforeInsert: no short check');
                    }
                }
            }
        } catch (Exception $e) {
            Minz_Log::error('YouTubeChannel2RssFeed-EntryBeforeInsert: ' . $e->getMessage());
        }

        Minz_Log::debug('YouTubeChannel2RssFeed-EntryBeforeInsert - END');
        return $entry;
    }

    private function isShort($entry, $extUrl, $ytID): bool {
        Minz_Log::debug('YouTubeChannel2RssFeed-isShort: START');
        $result = false;
        try {
            if (is_object($entry)) {
                // https://stackoverflow.com/questions/71192605/how-do-i-get-youtube-shorts-from-youtube-api-data-v3/71194751#71194751
                Minz_Log::debug('YouTubeChannel2RssFeed-isShort: entry is OBJ - ID=' . $ytID);

                $origAllowUrlFopen = ini_get('allow_url_fopen');

                $part = 'short';

                $shortDuration = $this->getShortDuration();
                if ($shortDuration > 0) {
                    $part .= ',contentDetails';
                }

                try {
                    Minz_Log::debug('YouTubeChannel2RssFeed-isShort: fOpen - start');
                    ini_set("allow_url_fopen", 1);
                    $json = file_get_contents($extUrl . '/videos?part=' . $part . '&id=' . $ytID);
                    Minz_Log::debug('YouTubeChannel2RssFeed-isShort: fOpen - data: ' . serialize($json));
                } finally {
                    ini_set("allow_url_fopen", $origAllowUrlFopen);
                    Minz_Log::debug('YouTubeChannel2RssFeed-isShort: fOpen - end');
                }

                $data = json_decode($json);

                if (isset($data) && isset($data->items) && (count($data->items) > 0)) {
                    Minz_Log::debug('YouTubeChannel2RssFeed-isShort: items found: ' . strval(count($data->items)));
                    foreach ($data->items as $item) {
                        Minz_Log::debug('YouTubeChannel2RssFeed-isShort: item = ' . serialize($item));

                        if (isset($item->id)) {
                            Minz_Log::debug('YouTubeChannel2RssFeed-isShort: id is set');
                            if ($item->id == $ytID) {
                                Minz_Log::debug('YouTubeChannel2RssFeed-isShort: id found: ' . $ytID);

                                // check short directly
                                if (($result == false) && (isset($item->short))) {
                                    Minz_Log::debug('YouTubeChannel2RssFeed-isShort: short is set');
                                    if (isset($item->short->available)) {
                                        Minz_Log::debug('YouTubeChannel2RssFeed-isShort: available is set');
                                        if ($item->short->available == true) {
                                            $result = true;
                                            Minz_Log::debug('YouTubeChannel2RssFeed-isShort: THIS IS A SHORT');
                                        }
                                    }
                                }

                                // check short by user defined duration
                                $extraLogTxt = '';
                                if (($result == false) && (isset($item->contentDetails))) {
                                    Minz_Log::debug('YouTubeChannel2RssFeed-isShort: contentDetails is set');
                                    if (isset($item->contentDetails->duration) && is_numeric($item->contentDetails->duration)) {
                                        Minz_Log::debug('YouTubeChannel2RssFeed-isShort: duration is set and numeric');
                                        $ytDuration = intval($item->contentDetails->duration);
                                        $extraLogTxt = '('.strval($ytDuration).'<'.strval($shortDuration).')';
                                        if ($ytDuration < $shortDuration) {
                                            Minz_Log::debug('YouTubeChannel2RssFeed-isShort: THIS IS A SHORT (by duration)'.$extraLogTxt);
                                            $result = true;
                                        }
                                    }
                                }

                                if ($result == false) {
                                    Minz_Log::debug('YouTubeChannel2RssFeed-isShort: THIS IS NO SHORT'.$extraLogTxt);
                                }
                                break; // video was found => exit 'foreach'
                            }
                        }
                    } // end of foreach
                }
            }
        } catch (Exception $e) {
            $result = false;
            Minz_Log::error('YouTubeChannel2RssFeed-isShort: ' . $e->getMessage());
        }

        Minz_Log::debug('YouTubeChannel2RssFeed-isShort: isShort - END - result = ' . ($result ? 'true' : 'false'));
        return $result;
    }
}
