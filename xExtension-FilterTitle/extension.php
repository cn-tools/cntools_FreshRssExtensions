<?php

class FilterTitleExtension extends Minz_Extension {
    private array $configurationErrors = [];
    private ?array $submittedConfiguration = null;

    public function init(): void {
        $this->registerTranslates();

        $this->registerHook('entry_before_insert', [$this, 'filterTitle']);
    }

    public function handleConfigureAction(): void {
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            $configuration = [
                'blacklist' => Minz_Request::paramTextToArray('blacklist', true),
                'mark_as_read' => Minz_Request::paramString('mark_as_read'),
                'whitelist' => Minz_Request::paramTextToArray('whitelist', true),
            ];
            $this->submittedConfiguration = $configuration;
            $this->configurationErrors = [];
            foreach (['blacklist', 'whitelist'] as $field) {
                $configuration[$field] = array_filter(array_map('trim', $configuration[$field]),
                    static fn(string $pattern): bool => $pattern !== '');
                foreach ($configuration[$field] as $line => $pattern) {
                    // Plain keywords and legacy regex delimiters remain supported.
                    if (str_starts_with($pattern, '/') && @preg_match($pattern, '') === false) {
                        $message = _t('ext.filter_title.invalid_pattern');
                        if ($message === 'ext.filter_title.invalid_pattern') {
                            // FreshRSS does not fall back for missing keys in an existing locale.
                            $english = require __DIR__ . '/i18n/en/ext.php';
                            $message = $english['filter_title']['invalid_pattern'];
                        }
                        $this->configurationErrors[] = sprintf($message, _t('ext.filter_title.' . $field), $line + 1);
                    }
                }
            }
            if ($this->configurationErrors !== []) {
                return;
            }
            $this->setSystemConfiguration($configuration);
        }
    }

    public function getConfigurationErrors(): array {
        return $this->configurationErrors;
    }

    public function filterTitle($entry) {
        if (is_object($entry) === true) {
            //-- do check BLACKLIST ---------------------------
            $patterns = $this->getSystemConfigurationValue('blacklist') ?? [];
            if (is_array($patterns)) {
                foreach ($patterns as $pattern) {
                    if (self::isPatternFound($entry->title(), $pattern) == true) {
                        if ($this->getSystemConfigurationValue('mark_as_read') == '1') {
                            // add entry into database and mark as read
                            $entry->_isRead(true);
                            return $entry;
                        } else {
                            // add entry into database not allowed
                            Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_keyword', $entry->title()));
                            return null;
                        }
                    }
                }
            }

            //-- do check WHITELIST ---------------------------
            $patterns = $this->getSystemConfigurationValue('whitelist') ?? [];
            if (is_array($patterns)) {
                foreach ($patterns as $pattern) {
                    if (self::isPatternFound($entry->title(), $pattern) == false) {
                        if ($this->getSystemConfigurationValue('mark_as_read') == '1') {
                            // add entry into database and mark as read
                            $entry->_isRead(true);
                            return $entry;
                        } else {
                            // add entry into database not allowed
                            Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_keyword', $entry->title()));
                            return null;
                        }
                    }
                }
            }
        }

        return $entry;
    }

    private function isPatternFound(string $title, string $pattern): bool {
        // A failed regex compile is expected for plain keywords.
        if (1 === @preg_match($pattern, $title)) {
            return true;
        } elseif (strpos($title, $pattern) !== false) {
            return true;
        }
        return false;
    }

    public function getMarkAsReadData(): bool {
        return ($this->submittedConfiguration['mark_as_read'] ?? $this->getSystemConfigurationValue('mark_as_read')) == '1';
    }

    public function getBlacklistData() {
        if ($this->submittedConfiguration !== null) {
            return implode(PHP_EOL, $this->submittedConfiguration['blacklist']);
        }
        if ($this->getSystemConfigurationValue('check_type') == '0') {
            // 20240311 - Until version v0.0.2 there was only blacklist OR whitelist availabe
            return implode(PHP_EOL, $this->getSystemConfigurationValue('blacklist_title_keywords') ?? []);
        } else {
            return implode(PHP_EOL, $this->getSystemConfigurationValue('blacklist') ?? []);
        }
    }

    public function getWhitelistData() {
        if ($this->submittedConfiguration !== null) {
            return implode(PHP_EOL, $this->submittedConfiguration['whitelist']);
        }
        if ($this->getSystemConfigurationValue('check_type') == '1') {
            // 20240311 - Until version v0.0.2 there was only blacklist OR whitelist availabe
            return implode(PHP_EOL, $this->getSystemConfigurationValue('blacklist_title_keywords') ?? []);
        } else {
            return implode(PHP_EOL, $this->getSystemConfigurationValue('whitelist') ?? []);
        }
    }
}
