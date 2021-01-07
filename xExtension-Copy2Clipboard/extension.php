<?php
class Copy2ClipboardExtension extends Minz_Extension {
	public function init() {
        $this->registerTranslates();
        Minz_View::appendScript($this->getFileUrl('clipboard.min.js', 'js'),'','','');
        Minz_View::appendScript($this->getFileUrl('copy2clipboard.js', 'js'),'','','');
		$this->registerHook('js_vars', array('Copy2ClipboardExtension', 'CntJavascriptVars'));
		$this->registerHook('nav_menu', array('Copy2ClipboardExtension', 'CntCopy2Clipboard'));
	}

    public static function CntJavascriptVars($vars){
        $vars['copy2clipboard']['msg_good_one'] = _t('ext.Copy2Clipboard.messages.good_one');
        $vars['copy2clipboard']['msg_good_more'] = _t('ext.Copy2Clipboard.messages.good_more');
        $vars['copy2clipboard']['msg_bad'] = _t('ext.Copy2Clipboard.messages.bad');
        return $vars;
    }

	public static function CntCopy2Clipboard() {
		$extension = Minz_ExtensionManager::findExtension('Copy2Clipboard');
		return '<a id="copy2clipboard" class="btn" href="#" title="' . _t('ext.Copy2Clipboard.title') . '"><img class="icon" src="' . $extension->getFileUrl('copy.svg', 'svg') . '" alt="' . _t('ext.Copy2Clipboard.title') . '"></a>';
	}
}
