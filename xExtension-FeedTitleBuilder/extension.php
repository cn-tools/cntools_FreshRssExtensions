<?php
class FeedTitleBuilderExtension extends Minz_Extension {
    public static $feedUrl = '';

    /*---------------------------- init ----------------------------*/
    public function init() {
        self::$feedUrl = '';
        $this->registerHook('feed_before_insert', array('FeedTitleBuilderExtension', 'CntBuildTitle'));
    }

    /*---------------------------- handleConfigureAction ----------------------------*/
    public function handleConfigureAction() {
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            $data = array();
            $data['template'] = Minz_Request::param('feedtitlebuilder_template', '');
            FreshRSS_Context::$user_conf->feedtitlebuilder = $data;
            FreshRSS_Context::$user_conf->save();
        }
    }

    /*---------------------------- CntBuildTitle ----------------------------*/
    public static function CntBuildTitle($feed) {
        try {
            if (is_object($feed) === true) {
                $lWorkTitle = $this->getConfigValueAsStrDef('template', '');
                if ($lWorkTitle == '') {
                    // do nothing
                } else {
                    // place original title
                    $lWorkTitle = str_replace('{origtitle}', $feed->name(), $lWorkTitle);

                    // transfer url variables
                    self::$feedUrl = $feed->url();
                    $regex = "#{url\b(.*?)\}(.*?){/url}#s";
                    $lWorkTitle = preg_replace_callback($regex, array('FeedTitleBuilderExtension', 'renderTitleBuilderPhpParseUrl'), $lWorkTitle);

                    // transfer date variables
                    $regex = "#{date\b(.*?)\}(.*?){/date}#s";
                    $lWorkTitle = preg_replace_callback($regex, array('FeedTitleBuilderExtension', 'renderTitleBuilderDate'), $lWorkTitle);

                    // write new title into feed
                    $feed->_name($lWorkTitle);
                }
            }
            return $feed;
        } catch (Exception $e) {
            Minz_Log::error($e->getMessage());
            return $feed;
        }
    }

    /*---------------------------- renderTitleBuilderPhpParseUrl ----------------------------*/
    private function renderTitleBuilderPhpParseUrl(&$matches){
        $urlValues = parse_url(self::$feedUrl);

        $hostValues = explode('.', $urlValues['host']);
        $urlValues['hosttld'] = array_pop($hostValues);
        $urlValues['hostname'] = array_pop($hostValues);
        $urlValues['hostsub'] = implode('.', $hostValues);

        return $urlValues[$matches[2]];
    }

    /*---------------------------- renderTitleBuilderDate ----------------------------*/
    private function renderTitleBuilderDate(&$matches){
        return date(trim($matches[2]));
    }

    /*---------------------------- getConfigValueAsStrDef ----------------------------*/
    public function getConfigValueAsStrDef(string $key, string $def):string {
        $res = FreshRSS_Context::$user_conf->feedtitlebuilder[$key] ?? $def;
        return strval($res);
    }
}
