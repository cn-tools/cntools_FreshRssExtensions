<?php

class FilterTitleExtension extends Minz_Extension {
    public function init(): void {
        $this->registerTranslates();

        $this->registerHook('entry_before_insert', [$this, 'filterTitle']);
    }

    public function handleConfigureAction(): void {
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            $configuration = [
                'check_type' => Minz_Request::param('check_type', '0'),
                'blacklist_title_keywords' => Minz_Request::paramTextToArray('blacklist_title_keywords', []),
            ];
            $this->setSystemConfiguration($configuration);
        }
    }

    public function filterTitle($entry) {
        $patterns = $this->getSystemConfigurationValue('blacklist_title_keywords') ?? [];
        if (is_array($patterns)) {
            $doRelease = (intval($this->getSystemConfigurationValue('blacklist_title_keywords', '0')) == 1);
            foreach ($patterns as $pattern) {
                if ($doRelease === true) {
                    if (1 === preg_match($pattern, $entry->title())) {
                        // do nothing
                    } elseif (strpos($entry->title(), $patterns) !== false) {
                        // do nothing
                    } else {
                        Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_keyword', $entry->title()));
                        return null;
                    }
                } else {
                    if (strpos($entry->title(), $patterns) !== false) {
                        Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_keyword', $entry->title()));
                        return null;
                    } elseif (1 === preg_match($pattern, $entry->title())) {
                        Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_keyword', $entry->title()));
                        return null;
                    }
                }
            }
        }
        return $entry;
    }

    public function getBlackKeywords() {
        return implode(PHP_EOL, $this->getSystemConfigurationValue('blacklist_title_keywords') ?? []);
    }
}
