<?php

class BlackListExtension extends Minz_Extension {
    public function init() {
        $this->registerTranslates();

        $this->registerHook('check_url_before_add', [$this, 'checkBlackList']);
    }
    
    public function handleConfigureAction() {
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            $configuration = [
                'blacklist_patterns' => Minz_Request::paramTextToArray('blacklist_patterns', ''),
            ];
            $this->setSystemConfiguration($configuration);
        }
    }

    public function checkBlackList($url) {
        foreach ($this->getSystemConfigurationValue('blacklist_patterns') as $pattern) {
            if (1 === preg_match("/{$pattern}/i", $url)) {
                Minz_Log::warning(_t('ext.black_list.warning.not_allowed_msg', $url));
                throw new FreshRSS_FeedNotAdded_Exception($url);
            }
        }
        return $url;
    }

    public function getBlackPatterns() {
        return implode(PHP_EOL, $this->getSystemConfigurationValue('blacklist_patterns') ?? []);
    }
}
