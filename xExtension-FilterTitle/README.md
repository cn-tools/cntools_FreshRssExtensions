# FreshRSS FilterTitle

A FreshRSS extension to filter feed entries by title.

## Documentation

You can define keywords to be used as blacklist or whitelist. Those on the blacklist have higher priority than those on the whitelist.

Each line is checked individually. In addition to normal texts, RegEx expressions can also be defined. These must be able to be evaluated using the PHP function `preg_match`.

Examples:

```text
some keyword
spam name
/\p{Latin}/i
other
```

For example, keep promotional title rules on separate lines rather than combining them into one expression:

```text
/\[Sponsor\]/i
/Promo Codes?/i
/Discount Codes?/i
/Coupons?/i
```

Plain text matches are case-sensitive. The `i` modifier makes regex matches case-insensitive. Blank lines and surrounding spaces are ignored.

Rules beginning with `/` are checked before saving. If a rule is invalid, the form shows its list and line number, keeps your input for correction, and leaves all saved settings unchanged. Other PHP regex delimiters remain supported for compatibility, but are not checked on save. Use `/` delimiters for validation.

Blocked entries are not added to the database by default. Choose **Mark as read** to import them as read instead. Rules apply to new entries during feed refresh; changing them does not remove existing articles.

## Tests

Run the regression tests with PHP and a FreshRSS checkout:

```sh
FRESHRSS_PATH=/path/to/FreshRSS php xExtension-FilterTitle/tests/run.php
```

The tests use the real FreshRSS extension, request and entry classes. Configuration and logs go into a temporary directory, which is removed afterwards. No running FreshRSS instance or database is needed.

## Translations

- English
- German

[![Crowdin](https://badges.crowdin.net/cntools-freshrssextensions/localized.svg)](https://crowdin.com/project/cntools-freshrssextensions)

You can help me to translate my extensions to a couple of languages on [Crowdin](https://crowdin.com/project/cntools-freshrssextensions). Or send me a new translation as pull request. I am happy to see!

## Special Thanks

- base developed by [6david9](https://github.com/6david9) with [PR](https://github.com/cn-tools/cntools_FreshRssExtensions/pull/9)
- [lamyergeier](https://github.com/lamyergeier) for testing
