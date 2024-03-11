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
                'blacklist' => Minz_Request::paramTextToArray('blacklist', []),
                'whitelist' => Minz_Request::paramTextToArray('whitelist', []),
            ];
            $this->setSystemConfiguration($configuration);
        }
    }

    public function filterTitle($entry) {
        if (is_object($entry) === true) {
            //-- do check BLACKLIST ---------------------------
            $patterns = $this->getSystemConfigurationValue('blacklist') ?? [];
            if (is_array($patterns)) {
                foreach ($patterns as $pattern) {
                    if (self::isPatternFound($entry->title(), $pattern) == true) {
                        Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_keyword', $entry->title()));
                        return null;
                    }
                }
            }

            //-- do check WHITELIST ---------------------------
            $patterns = $this->getSystemConfigurationValue('whitelist') ?? [];
            if (is_array($patterns)) {
                foreach ($patterns as $pattern) {
                    if (self::isPatternFound($entry->title(), $pattern) == false) {
                        Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_keyword', $entry->title()));
                        return null;
                    }
                }
            }
        }

        return $entry;
    }

    private function isPatternFound(string $title, string $pattern): bool {
        if (1 === preg_match($pattern, $title)) {
            return true;
        } elseif (strpos($title, $pattern) !== false) {
            return true;
        }
        return false;
    }

    public function getBlacklistData() {
        if ($this->getSystemConfigurationValue('check_type') == '0') {
            // 20240311 - Until version v0.0.2 there was only blacklist OR whitelist availabe
            return implode(PHP_EOL, $this->getSystemConfigurationValue('blacklist_title_keywords') ?? []);
        } else {
            return implode(PHP_EOL, $this->getSystemConfigurationValue('blacklist') ?? []);
        }
    }

    public function getWhitelistData() {
        if ($this->getSystemConfigurationValue('check_type') == '1') {
            // 20240311 - Until version v0.0.2 there was only blacklist OR whitelist availabe
            return implode(PHP_EOL, $this->getSystemConfigurationValue('blacklist_title_keywords') ?? []);
        } else {
            return implode(PHP_EOL, $this->getSystemConfigurationValue('whitelist') ?? []);
        }
    }
}
