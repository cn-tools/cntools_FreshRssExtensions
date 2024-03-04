<?php

class RemoveEmojisExtension extends Minz_Extension {
    public function init() {
        $this->registerHook('entry_before_insert', [$this, 'cntRemoveEmojis']);
    }

    public function cntRemoveEmojis($entry) {
        if (is_object($entry) === true) {
            try {
                $baseTitle = $entry->title();
                $baseTitle = trim($baseTitle);
                if ($baseTitle != '') {
                    $entry->_title(self::remove_emoji($baseTitle));
                }
            } catch (Exception $e) {
                Minz_Log::error($e->getMessage());
            }
        }
        return $entry;
    }

    private function remove_emoji(string $string): string
    {
        /**
         * This methode is written from
         * - https://stackoverflow.com/users/505340/xtophe ( https://stackoverflow.com/a/68155491 )
         * - https://stackoverflow.com/users/2944137/lukas-pierce ( https://stackoverflow.com/a/75442520 )
         */

        /**
         * @see https://unicode.org/charts/PDF/UFE00.pdf
         */
        $variant_selectors = '[\x{FE00}–\x{FE0F}]?'; // ? - optional

        /**
         * There are many sets of modifiers
         * such as skin color modifiers and etc
         *
         * Not used, because this range already included
         * in 'Match Miscellaneous Symbols and Pictographs' range
         * $skin_modifiers = '[\x{1F3FB}-\x{1F3FF}]';
         *
         * Full list of modifiers:
         * https://unicode.org/emoji/charts/full-emoji-modifiers.html
         */

        // Match Enclosed Alphanumeric Supplement
        $regex_alphanumeric = '/[\x{1F100}-\x{1F1FF}]' . $variant_selectors . '/u';
        $clear_string = preg_replace($regex_alphanumeric, '', $string);

        // Match Miscellaneous Symbols and Pictographs
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]' . $variant_selectors . '/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Emoticons
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]' . $variant_selectors . '/u';
        $clear_string = preg_replace($regex_emoticons, '', $clear_string);

        // Match Transport And Map Symbols
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]' . $variant_selectors . '/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Supplemental Symbols and Pictographs
        $regex_supplemental = '/[\x{1F900}-\x{1F9FF}]' . $variant_selectors . '/u';
        $clear_string = preg_replace($regex_supplemental, '', $clear_string);

        // Match Miscellaneous Symbols
        $regex_misc = '/[\x{2600}-\x{26FF}]' . $variant_selectors . '/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats
        $regex_dingbats = '/[\x{2700}-\x{27BF}]' . $variant_selectors . '/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }
}
