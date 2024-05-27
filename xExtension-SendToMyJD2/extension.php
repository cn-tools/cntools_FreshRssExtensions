<?php
require_once __DIR__ . '/static/myjdapi_class.php';

class SendToMyJD2Extension extends Minz_Extension {
    const CNT_REQUIRDED_FRESHRSS_VERSION = '1.20';
    const CNT_DATE_REGEX = "#{date\b(.*?)\}(.*?){/date}#s";
    const CNT_TEST_YT_LINK = 'https://www.youtube.com/watch?v=RC4HRPbQrxM';
    private $cntMyJdMD = null;

	/*---- init ----*/
	public function init() {
        $this->registerTranslates();
		$this->registerHook('entry_before_insert', array($this, 'CntEntryBeforeInsert'));
	}

	/*---- handleConfigureAction ----*/
    public function handleConfigureAction() {
        Minz_Log::debug('SendToMyJD2 - handleConfigureAction: start');
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            Minz_Log::debug('SendToMyJD2 - handleConfigureAction: isPost=TRUE');
            $data = [];
            $data['email'] = strval(Minz_Request::param('SendToMyJD2_email', ''));
            $data['password_plain'] = strval(Minz_Request::param('SendToMyJD2_password', ''));
            $data['sendtestdata'] = strval(Minz_Request::paramString('SendToMyJD2_sendtestdata'));
            $data['device'] = strval(Minz_Request::param('SendToMyJD2_device', ''));
            $data['patterns'] = array_filter(Minz_Request::paramTextToArray('SendToMyJD2_patterns', []));

            Minz_Log::debug('SendToMyJD2 - handleConfigureAction: data2save=' . var_export($data, true));

            if (isset($this->cntMyJdMD)) {
                Minz_Log::debug('SendToMyJD2 - handleConfigureAction: unset old myJD model');
                unset($this->cntMyJdMD);
            }

            Minz_Log::debug('SendToMyJD2 - handleConfigureAction: try connecting to myJD instance...');
            try {
                $lMD = $this->getMyJdMD();
                if (!isset($lMD)) {
                    Minz_Log::warning('SendToMyJD2 - handleConfigureAction: connection problem!');
                } else {
                    if (Minz_Request::paramString('SendToMyJD2_sendtestdata') == '1') {
                        Minz_Log::debug('SendToMyJD2 - handleConfigureAction: send test data...');
                        $lMD->addLinks(self::CNT_TEST_YT_LINK, 'FreshRSS-SendToMyJD2-TestLink_' . date('Ymd') . '_' . date('His')/*packageName*/, false/*autostart*/);
                    }
                    Minz_Log::debug('SendToMyJD2 - handleConfigureAction: getLastPostFields=' . serialize($lMD->getProtokoll()));
                    Minz_Log::debug('SendToMyJD2 - handleConfigureAction: connecting OK');
                }
            } catch (Exception $e) {
                Minz_Log::error('SendToMyJD2-try connecting myJD: ' . $e->getMessage());
            }

            FreshRSS_Context::$user_conf->SendToMyJD2 = $data;
            FreshRSS_Context::$user_conf->save();
        }
        Minz_Log::debug('SendToMyJD2 - handleConfigureAction: end');
    }

	/*---- install ----*/
	public function install() {
        if (version_compare(strval(FRESHRSS_VERSION), strval(self::CNT_REQUIRDED_FRESHRSS_VERSION) , '<')){
            $this->registerTranslates();
            return _t('ext.SendToMyJD2.install.bad_freshrss', self::CNT_REQUIRDED_FRESHRSS_VERSION, FRESHRSS_VERSION);
        }
		return true;
	}

	/*---- getDataPatterns ----*/
    public function getDataPatterns() {
        return implode(PHP_EOL, FreshRSS_Context::$user_conf->SendToMyJD2['patterns'] ?? []);
    }

    /*---- getPatternDataAsArray():array ----*/
    private function getPatternDataAsArray($input=NULL): array {
        Minz_Log::debug('SendToMyJD2 - getPatternDataAsArray: input=' . serialize($input));

        $result = [];

        if (!isset($input)) {
            $input = FreshRSS_Context::$user_conf->SendToMyJD2['patterns'] ?? [];
            Minz_Log::debug('SendToMyJD2 - getPatternDataAsArray: readConfig=' . serialize($input));
        }

        // transfer pattern into array
        foreach ($input as &$line) {
            $lineResult = [];
            $lineArray = str_getcsv($line, ';');

            foreach ($lineArray as $cell) {
                $cellArray = str_getcsv($cell, '=');
                if (count($cellArray) === 2) {
                    $lineResult[$cellArray[0]] = $cellArray[1];
                }
            }

            if (array_key_exists('t', $lineResult) && ($lineResult['t'] != '')) {
                $lineResult['t'] = str_getcsv($lineResult['t'], '|');
            }

            $result[] = $lineResult;
        }
        unset($line);

        Minz_Log::debug('SendToMyJD2 - getPatternDataAsArray: result=' . var_export($result, true));

        return $result;
    }

    /*---- getCatIDandFeedID ----*/
    private function getCatIDandFeedID($entry):array {
        $catID = null;
        $feedID = null;

        if (isset($entry) && is_object($entry)) {
            if (null !== $entry->feed()) {
                $feedID = $entry->feed()->id();
                $feedID = strtolower(strval($feedID));
                Minz_Log::debug('SendToMyJD2 - getCatIDandFeedID: FeedID=' . $feedID);

                if (null !== $entry->feed()->category()) {
                    $catID = $entry->feed()->category()->id();
                    $catID = strtolower(strval($catID));
                    Minz_Log::debug('SendToMyJD2 - getCatIDandFeedID: CatID=' . $catID);
                }
            }
        }

        $found = isset($feedID) || isset($catID);

        Minz_Log::debug('SendToMyJD2 - getCatIDandFeedID: found=' . var_export($found, true) . ', cadID=' . var_export($catID, true) . ', feedID=' . var_export($feedID, true));
        return [$found, $catID, $feedID];
    }

	/*---- CntEntryBeforeInsert ----*/
	public function CntEntryBeforeInsert($entry) {
        Minz_Log::debug('SendToMyJD2 - CntEntryBeforeInsert: start');
        if ((isset($entry)) && is_object($entry)) {
            list($found, $catID, $feedID) = $this->getCatIDandFeedID($entry);

            if ($found) {
                Minz_Log::debug('SendToMyJD2 - CntEntryBeforeInsert: entry=' . serialize($entry));
                $patterns = $this->getPatternDataAsArray();

/*
k=key
v=value
r=regex => title of new feed entry only
p=packagename
m=markasread
t=tags
a=autostart
*/

                foreach ($patterns as &$elem) {
                    Minz_Log::debug('SendToMyJD2 - CntEntryBeforeInsert: elem=' . serialize($elem));
                    if (array_key_exists('k', $elem)) {
                        $key = strtolower(strval($elem['k']));

                        $value = null;
                        if (array_key_exists('v', $elem)) {
                            $value = strtolower(strval($elem['v']));
                        }

                        switch ($key) {
                            case "*":       // all
                                Minz_Log::debug('SendToMyJD2 - CntEntryBeforeInsert: key=* (star)');
                                $this->prepareData($entry, $elem);
                                break;
                            case "c":       // category
                                Minz_Log::debug('SendToMyJD2 - CntEntryBeforeInsert: check by catID: need=' . strval($value) . ', current=' . strval($catID));
                                if (isset($value) && isset($catID) && ($value == $catID)) {
                                    $this->prepareData($entry, $elem);
                                }
                                break;
                            case "f":       // feed
                                Minz_Log::debug('SendToMyJD2 - CntEntryBeforeInsert: check by feedID: need=' . strval($value) . ', current=' . strval($feedID));
                                if (isset($value) && isset($feedID) && ($value == $feedID)) {
                                    $this->prepareData($entry, $elem);
                                }
                                break;
                        }
                    }
                }
            }
        }

        Minz_Log::debug('SendToMyJD2 - CntEntryBeforeInsert: end');
        return $entry;
	}

	/*---- prepareData ----*/
    private function prepareData($entry, $data): void {
        Minz_Log::debug('SendToMyJD2 - prepareData: start');
        if (isset($entry) && isset($data) && is_array($data)) {

            // check regex pattern
            if (array_key_exists('r', $data)) {
                $regexPattern = strval(trim($data['r']));
                Minz_Log::debug('SendToMyJD2 - prepareData: regex=' . serialize($regexPattern));
                if ($regexPattern != '') {
                    if (1 !== preg_match($regexPattern, $entry->title())) {
                        // regex expression not found => sending to MyJD2 is not allowed
                        Minz_Log::debug('SendToMyJD2 - prepareData: regex expression not found');
                        return;
                    }
                }
            }

            // check and build packageName
            $packageName = null;
            if (array_key_exists('p', $data)) {
                $packageName = strval($data['p']);

                // transfer userid
                $packageName = str_replace('{userid}', trim(Minz_Session::param('currentUser')), $packageName);

                // transfer date variables
                $packageName = preg_replace_callback(self::CNT_DATE_REGEX, array($this, 'renderDateRegEx'), $packageName);

                $packageName = trim($packageName);
                if ($packageName == '') {
                    unset($packageName);
                }
                Minz_Log::debug('SendToMyJD2 - prepareData: packageName=' . strval($packageName));
            }

            // check flag autostart
            $autostart = (array_key_exists('a', $data) && (strval($data['a']) == '1')) ? true : false;

            // send links
            if ($this->doSendLink($entry, $entry->link(), $packageName, $autostart) === true) {
                if (array_key_exists('m', $data) && (strval($data['m']) == '1')) {
                    Minz_Log::notice('SendToMyJD2 - sent to JD2 and marked as read; entryID=' . strval($entry->id()));
                    $entry->_isRead(true);
                } else {
                    Minz_Log::notice('SendToMyJD2 - sent to JD2; entryID=' . strval($entry->id()));
                }

/* --- TAGS NOT WORKING PROPERLY YET -----------------------------------------------------------------------------------------------------------------------------
                if (array_key_exists('t', $data)) {
                    $tags = $data['t'];
                    if (is_array($tags)) {
                        $resultTags = [];
                        foreach ($tags as $tag) {
                            // transfer userid
                            $workTag = str_replace('{userid}', trim(Minz_Session::param('currentUser')), $tag);

                            // transfer date variables
                            $resultTags[] = preg_replace_callback(self::CNT_DATE_REGEX, array($this, 'renderDateRegEx'), $workTag);
                        }
                        if (count($resultTags) > 0){
                            $entry->_tags(array_unique(array_merge($resultTags, $entry->tags())));
                        }
                    }
                }
--- TAGS NOT WORKING PROPERLY YET -----------------------------------------------------------------------------------------------------------------------------*/
            }
        }
    }

	/*---- doSendLink ----*/
    private function doSendLink($entry, $url, $packageName = null, $autostart = false): bool {
        Minz_Log::debug('SendToMyJD2 - doSendLink: start - url=' . $url);
        $result = false;
        if (isset($entry) && ($url != '')) {
            $lMD = $this->getMyJdMD();
            if (isset($lMD) && ($lMD->addLinks($url, $packageName, ($autostart == true)) === true)) {
                Minz_Log::debug('SendToMyJD2 - doSendLink: done');
                $result = true;
            }
            Minz_Log::debug('SendToMyJD2 - doSendLink: getLastPostFields=' . serialize($lMD->getProtokoll()));
        }

        Minz_Log::debug('SendToMyJD2 - doSendLink: end - ' . ($result ? 'true' : 'false'));
        return $result;
    }

    /*---- getMyJdMD ----*/
    private function getMyJdMD(){
        $lMD = $this->cntMyJdMD;

        if (isset($lMD)){
            Minz_Log::debug('SendToMyJD2 - getMyJdMD: befor reconnect');
            if (!$lMD->reconnect()){
                Minz_Log::debug('SendToMyJD2 - getMyJdMD: reconnect faild');
                unset($lMD);
            }
        }

        if (!isset($lMD)){
            Minz_Log::debug('SendToMyJD2 - getMyJdMD: create model');
            $email = FreshRSS_Context::$user_conf->SendToMyJD2['email'];
            $password = FreshRSS_Context::$user_conf->SendToMyJD2['password_plain'];
            $device = FreshRSS_Context::$user_conf->SendToMyJD2['device'];
            $appkey =  'FreshRSS-' . trim(Minz_Session::param('currentUser')) . '-' . uniqid('');

            Minz_Log::debug('SendToMyJD2 - getMyJdMD: email=' . $email . ';pass=' . $password . ';device=' . $device . ';appkey=' . $appkey);

            $lMD = new MYJDAPI($email, $password, $device, $appkey, true);
            if($lMD == false) {
                // connection error
                Minz_Log::error('SendToMyJD2 - getMyJdMD: connection error!');
                unset($lMD);
                $this->cntMyJdMD = $lMD;
            }
        }

        Minz_Log::debug('SendToMyJD2 - getMyJdMD: model=' . serialize($lMD));
        return $lMD;
    }

	/*---- renderDateRegEx ----*/
	private function renderDateRegEx(&$matches){
		return date(trim($matches[2]));
	}

    /*----- getYouTubeTestLink ----*/
    public function getYouTubeTestLink() {
        return self::CNT_TEST_YT_LINK;
    }

    /*----- getDevicesDatalistHTML ----*/
    public function getDevicesDatalistHTML() {
        $result = '';
        $lMD = $this->getMyJdMD();
        if (isset($lMD)) {
            $devices = $lMD->getAvailableDevices();
            if (is_array($devices)) {
                $result = '<datalist id="SendToMyJD2_devices">';
                foreach ($devices as $device) {
                    $result .= '<option value="' . $device['name'] . '"></option>';
                }
                $result .= '</datalist>';
            }
        }
        return $result;
    }
}
