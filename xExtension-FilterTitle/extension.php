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
                'blacklist_title_keywords' => $this->parseKeywords(Minz_Request::paramString('blacklist_title_keywords')),
            ];
            $this->setSystemConfiguration($configuration);
        }
    }

    private function parseKeywords($string) {
        if (isset($string) && is_string($string)) {
            return explode("\n", $string);
        }
        return [];
    }

    public function filterTitle($entry) {
        foreach ($this->getSystemConfigurationValue('blacklist_title_keywords') as $keyword) {
            if (strpos($entry->title(), $keyword) !== false) {
                Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_keyword', $entry->title()));
                return null;
            }
        }
        return $entry;
    }

    public function getBlackKeywords() {
        return implode("\n", $this->getSystemConfigurationValue('blacklist_title_keywords') ?? []);
    }
}
