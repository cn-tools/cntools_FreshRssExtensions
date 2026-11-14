<?php

class FilterTitleExtension extends Minz_Extension {
    private array $configurationErrors = [];
    private ?array $submittedConfiguration = null;
    private array $reportedErrors = [];
    private ?array $storedRules = null;
    private const RULE_FIELDS = ['title', 'authors', 'link', 'tags', 'thumbnail_url', 'guid'];
    private const RULE_OPERATORS = ['contains', 'equals', 'starts_with', 'ends_with', 'regex'];

    public function init(): void {
        $this->registerTranslates();

        $this->registerHook('entry_before_insert', [$this, 'filterTitle']);
        if (Minz_Request::controllerName() === 'extension') {
            // The AJAX settings slider does not load scripts from the response head.
            Minz_View::appendScript($this->getFileUrl('configure.js'), false, true, false);
        }
    }

    public function handleConfigureAction(): void {
        $this->registerTranslates();
        Minz_View::appendScript($this->getFileUrl('configure.js'), false, true, false);

        if (Minz_Request::isPost()) {
            $configuration = [
                'blacklist' => Minz_Request::paramTextToArray('blacklist', true),
                'mark_as_read' => Minz_Request::paramString('mark_as_read'),
                'whitelist' => Minz_Request::paramTextToArray('whitelist', true),
            ];
            $params = Minz_Request::params();
            $hasEditor = array_key_exists('rules_present', $params);
            $configuration['rules'] = $hasEditor
                ? (array_key_exists('rules', $params) ? $params['rules'] : [])
                : $this->getSystemConfigurationValue('rules', []);
            $this->submittedConfiguration = $configuration;
            $this->configurationErrors = [];
            if (($hasEditor && $params['rules_present'] !== '1') || (!$hasEditor && array_key_exists('rules', $params))) {
                $this->configurationErrors[] = $this->getRuleTextData('invalid_rules');
            }
            if (array_key_exists('rules_action', $params)) {
                // Without JavaScript (including a disabled extension's slider), row buttons redisplay, never save.
                $rules = $configuration['rules'];
                $action = $params['rules_action'];
                if ($this->configurationErrors !== [] || !$hasEditor || !is_array($rules) || !array_is_list($rules)) {
                    $this->configurationErrors[] = $this->getRuleTextData('invalid_rules');
                } elseif ($action === 'add') {
                    $this->submittedConfiguration['rules'][] = [
                        'field' => 'title', 'operator' => 'contains', 'value' => '', 'case_sensitive' => false,
                    ];
                } elseif (is_string($action) && preg_match('/^remove_(0|[1-9][0-9]*)$/D', $action, $match)
                    && array_key_exists((int)$match[1], $rules)) {
                    unset($rules[(int)$match[1]]);
                    $this->submittedConfiguration['rules'] = array_values($rules);
                } else {
                    $this->configurationErrors[] = $this->getRuleTextData('invalid_rules');
                }
                return;
            }
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
            $configuration['rules'] = $this->parseRules($configuration['rules'], $hasEditor, $this->configurationErrors);
            if ($this->configurationErrors !== []) {
                return;
            }
            $this->setSystemConfiguration($configuration);
            $this->storedRules = $configuration['rules'];
        }
    }

    public function getConfigurationErrors(): array {
        if ($this->submittedConfiguration === null) {
            $this->getStoredRules();
        }
        return $this->configurationErrors;
    }

    public function filterTitle($entry) {
        if ($entry instanceof FreshRSS_Entry) {
            $fields = [];
            foreach ($this->getStoredRules() as $index => $rule) {
                $field = $rule['field'];
                $fields[$field] ??= match ($field) {
                    'title' => [$entry->title()],
                    'authors' => $entry->authors(),
                    'link' => [html_entity_decode($entry->link(true), ENT_QUOTES | ENT_HTML5, 'UTF-8')],
                    'tags' => $entry->tags(),
                    'thumbnail_url' => [html_entity_decode($entry->thumbnail(false)['url'] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')],
                    'guid' => [$entry->guid()],
                };
                foreach ($fields[$field] as $fieldValue) {
                    $literalCase = $rule['case_sensitive'] || $rule['operator'] === 'regex';
                    $value = $literalCase ? $fieldValue : mb_convert_case($fieldValue, MB_CASE_FOLD, 'UTF-8');
                    $pattern = $literalCase ? $rule['value'] : mb_convert_case($rule['value'], MB_CASE_FOLD, 'UTF-8');
                    $matches = $value !== '' && match ($rule['operator']) {
                        'contains' => str_contains($value, $pattern),
                        'equals' => $value === $pattern,
                        'starts_with' => str_starts_with($value, $pattern),
                        'ends_with' => str_ends_with($value, $pattern),
                        'regex' => $this->regexMatches($pattern, $value, $index, $field),
                    };
                    if ($matches) {
                        if ($this->getSystemConfigurationValue('mark_as_read') == '1') {
                            $entry->_isRead(true);
                            return $entry;
                        }
                        Minz_Log::warning(_t('ext.filter_title.warning.not_allowed_msg', $entry->title()));
                        return null;
                    }
                }
            }
        }
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

    private function logOnce(string $identifier, string $message): void {
        if (!isset($this->reportedErrors[$identifier])) {
            $this->reportedErrors[$identifier] = true;
            Minz_Log::warning('FilterTitle: ' . $message);
        }
    }

    private function regexMatches(string $pattern, string $value, int $index, string $field): bool {
        $result = @preg_match($this->boundedPattern($pattern), $value);
        if ($result === false) {
            $error = preg_last_error_msg();
            $this->logOnce('regex:' . $index . ':' . $error,
                'Additional blacklist rule ' . ($index + 1) . ' (' . $field . '): ' . $error . '. Check or simplify this rule.');
        }
        return $result === 1;
    }

    private function boundedPattern(string $pattern): string {
        $pattern = ltrim($pattern);
        $delimiter = $pattern[0];
        $limits = '(*LIMIT_MATCH=100000)(*LIMIT_DEPTH=1000)';
        $closing = ['(' => ')', '[' => ']', '{' => '}', '<' => '>'][$delimiter] ?? $delimiter;
        $end = strrpos($pattern, $closing);
        $body = substr($pattern, 1, $end - 1);
        $prefix = '';
        $offset = 0;
        // PCRE uses the last repeated limit. Clamp supplied start directives as well.
        while (preg_match('/\G\(\*([A-Z0-9_]+)(?:=([0-9]+))?\)/', $body, $directive, 0, $offset) === 1) {
            $name = $directive[1];
            $cap = match ($name) {
                'LIMIT_MATCH' => 100000,
                'LIMIT_DEPTH', 'LIMIT_RECURSION' => 1000,
                default => null,
            };
            $prefix .= $cap !== null ? '(*' . $name . '=' . min($cap, (int)$directive[2]) . ')' : $directive[0];
            $offset += strlen($directive[0]);
        }
        $body = $prefix . substr($body, $offset);
        if ($delimiter === '(' || !str_contains($limits, $delimiter)) {
            return $delimiter . $limits . $body . $closing . substr($pattern, $end + 1);
        }
        // A fresh delimiter preserves escapes and \\Q quoting without rewriting the body.
        for ($byte = 1; $byte <= 255; $byte++) {
            $replacement = chr($byte);
            if (!ctype_alnum($replacement) && !ctype_space($replacement) && $replacement !== '\\'
                && !str_contains($body, $replacement)) {
                return $replacement . $limits . $body . $replacement . substr($pattern, $end + 1);
            }
        }
        return ''; // Refuse a pattern that cannot be safely bounded, rather than run it unbounded.
    }

    private function parseRules($rules, bool $fromForm, array &$errors): array {
        if (!is_array($rules) || !array_is_list($rules)) {
            $errors[] = $this->getRuleTextData('invalid_rules');
            return [];
        }
        $parsed = [];
        foreach ($rules as $index => $rule) {
            $caseSensitive = is_array($rule) && array_key_exists('case_sensitive', $rule)
                ? $rule['case_sensitive'] : ($fromForm ? false : null);
            if (!is_array($rule)
                || array_diff(array_keys($rule), ['field', 'operator', 'value', 'case_sensitive']) !== []
                || !in_array($rule['field'] ?? null, self::RULE_FIELDS, true)
                || !in_array($rule['operator'] ?? null, self::RULE_OPERATORS, true)
                || !is_string($rule['value'] ?? null) || preg_match('/\S/u', $rule['value']) !== 1
                || ($fromForm ? !in_array($caseSensitive, [true, false, '1', '0', ''], true) : !is_bool($caseSensitive))) {
                $errors[] = sprintf($this->getRuleTextData('invalid_rule'), $index + 1);
                continue;
            }
            if ($rule['operator'] === 'regex' && (@preg_match($rule['value'], '') === false
                || @preg_match($this->boundedPattern($rule['value']), '') === false)) {
                $errors[] = sprintf($this->getRuleTextData('invalid_rule_regex'), $index + 1);
                continue;
            }
            $rule['case_sensitive'] = in_array($rule['case_sensitive'] ?? false, [true, '1'], true);
            $parsed[] = $rule;
        }
        return $parsed;
    }

    public function getRulesData(): array {
        if ($this->submittedConfiguration !== null) {
            $rules = $this->submittedConfiguration['rules'];
            if (!is_array($rules)) {
                $rules = [['value' => $rules]];
            }
            return array_map(function ($rule): array {
                $rule = is_array($rule) ? $rule : ['value' => $rule];
                $display = static fn($value): string => is_string($value) ? $value
                    : (json_encode($value, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE) ?: '');
                return [
                    'field' => $display($rule['field'] ?? ''),
                    'operator' => $display($rule['operator'] ?? ''),
                    'value' => $display($rule['value'] ?? ''),
                    'case_sensitive' => in_array($rule['case_sensitive'] ?? false, [true, '1'], true),
                ];
            }, array_values($rules));
        }
        return $this->getStoredRules();
    }

    private function getStoredRules(): array {
        if ($this->storedRules !== null) {
            return $this->storedRules;
        }
        $errors = [];
        $rules = $this->parseRules($this->getSystemConfigurationValue('rules', []), false, $errors);
        if ($errors !== []) {
            $message = $this->getRuleTextData('stored_rules_error');
            $this->configurationErrors[] = $message;
            $this->logOnce('configuration', $message . ' ' . implode(' ', $errors));
            return $this->storedRules = [];
        }
        return $this->storedRules = $rules;
    }

    public function getRuleFieldsData(): array {
        return array_combine(self::RULE_FIELDS, array_map(fn($field) => $this->getRuleTextData('field_' . $field), self::RULE_FIELDS));
    }

    public function getRuleOperatorsData(): array {
        return array_combine(self::RULE_OPERATORS, array_map(fn($operator) => $this->getRuleTextData('operator_' . $operator), self::RULE_OPERATORS));
    }

    public function getRuleTextData(string $key): string {
        $message = _t('ext.filter_title.' . $key);
        if ($message === 'ext.filter_title.' . $key) {
            $english = require __DIR__ . '/i18n/en/ext.php';
            return $english['filter_title'][$key];
        }
        return $message;
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
