<?php
class Copy2ClipboardExtension extends Minz_Extension {
    const CNT_REQUIRDED_FRESHRSS_VERSION = '1.18';

	public function init() {
        $this->registerTranslates();
        Minz_View::appendScript($this->getFileUrl('clipboard.min.js', 'js'),'','','');
        Minz_View::appendScript($this->getFileUrl('copy2clipboard.js', 'js'),'','','');
		$this->registerHook('js_vars', array('Copy2ClipboardExtension', 'CntJavascriptVars'));
		$this->registerHook('nav_menu', array('Copy2ClipboardExtension', 'CntCopy2Clipboard'));
	}

    public function handleConfigureAction() {
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            $data = [];
            $data['findcolor'] = Minz_Request::param('copy2clipboard_findcolor', '0');
            $data['color'] = Minz_Request::param('copy2clipboard_color', '');
            FreshRSS_Context::$user_conf->Copy2Clipboard = $data;
            FreshRSS_Context::$user_conf->save();
        }
    }

	public function install() {
        if (version_compare(FRESHRSS_VERSION, self::CNT_REQUIRDED_FRESHRSS_VERSION , '<')){
            $this->registerTranslates();
            return _t('ext.Copy2Clipboard.install.bad_freshrss', self::CNT_REQUIRDED_FRESHRSS_VERSION, FRESHRSS_VERSION);
        }
		return true;
	}

    public static function CntJavascriptVars($vars){
        $vars['copy2clipboard']['config']['findcolor'] = FreshRSS_Context::$user_conf->Copy2Clipboard["findcolor"];
        $vars['copy2clipboard']['config']['color'] = FreshRSS_Context::$user_conf->Copy2Clipboard["color"];
        $vars['copy2clipboard']['messages']['good_one'] = _t('ext.Copy2Clipboard.messages.good_one');
        $vars['copy2clipboard']['messages']['good_more'] = _t('ext.Copy2Clipboard.messages.good_more');
        $vars['copy2clipboard']['messages']['bad'] = _t('ext.Copy2Clipboard.messages.bad');
        return $vars;
    }

	public static function CntCopy2Clipboard() {
        $svg = '<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path id="cnt_copy2clip_svg_path" d="M433.941 65.941l-51.882-51.882A48 48 0 0 0 348.118 0H176c-26.51 0-48 21.49-48 48v48H48c-26.51 0-48 21.49-48 48v320c0 26.51 21.49 48 48 48h224c26.51 0 48-21.49 48-48v-48h80c26.51 0 48-21.49 48-48V99.882a48 48 0 0 0-14.059-33.941zM266 464H54a6 6 0 0 1-6-6V150a6 6 0 0 1 6-6h74v224c0 26.51 21.49 48 48 48h96v42a6 6 0 0 1-6 6zm128-96H182a6 6 0 0 1-6-6V54a6 6 0 0 1 6-6h106v88c0 13.255 10.745 24 24 24h88v202a6 6 0 0 1-6 6zm6-256h-64V48h9.632c1.591 0 3.117.632 4.243 1.757l48.368 48.368a6 6 0 0 1 1.757 4.243V112z"/></svg>';
		return '<a id="copy2clipboard" class="btn" href="#" title="' . _t('ext.Copy2Clipboard.button.title') . '">' . $svg . '</a>';
	}
}
