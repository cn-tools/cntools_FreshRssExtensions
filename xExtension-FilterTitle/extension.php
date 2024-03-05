<?php

class FilterTitleExtension extends Minz_Extension {
    public function init() {
        $this->registerTranslates();

        $this->registerHook('entry_before_insert', [$this, 'filterTitle']);
    }
    
    public function handleConfigureAction() {
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            $configuration = [
                'blacklist_title_keywords' => Minz_Request::paramTextToArray('blacklist_title_keywords', ''),
            ];
            $this->setSystemConfiguration($configuration);
        }
    }

    public function filterTitle($entry) {
        foreach ($this->getSystemConfigurationValue('blacklist_title_keywords') as $pattern) {
            if (1 === preg_match("/{$pattern}/i", $entry->title())) {
                Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_keyword', $entry->title()));
                return null;
            }
        }
        return $entry;
    }

    public function getBlackKeywords() {
        return implode(PHP_EOL, $this->getSystemConfigurationValue('blacklist_title_keywords') ?? []);
    }
}
