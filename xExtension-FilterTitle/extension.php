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
                'blacklist_title_keywords' => Minz_Request::paramTextToArray('blacklist_title_keywords', []),
            ];
            $this->setSystemConfiguration($configuration);
        }
    }

    public function filterTitle($entry) {
        $patterns = $this->getSystemConfigurationValue('blacklist_title_keywords') ?? [];
        if (is_array($patterns)) {
            foreach ($patterns as $pattern) {
                if (1 === preg_match("/{$pattern}/i", $entry->title())) {
                    Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_keyword', $entry->title()));
                    return null;
                }
            }
        }
        return $entry;
    }

    public function getBlackKeywords() {
        return implode(PHP_EOL, $this->getSystemConfigurationValue('blacklist_title_keywords') ?? []);
    }
}
