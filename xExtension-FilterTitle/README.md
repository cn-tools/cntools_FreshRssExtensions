# FreshRSS FilterTitle

A FreshRSS extension to block feed entries by title, authors, link, tags, thumbnail URL or GUID.

## Title blacklist and whitelist

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

Use `/` delimiters for regex rules so they can be checked before saving.

Blocked entries are not added to the database by default. Choose **Mark as read** to import them as read instead. Rules apply to new entries during feed refresh; changing them does not remove existing articles.

## Additional blacklist rules

Below the title lists, choose **Add rule**, a field, a match type and a value. Remove a row with **Remove rule**. Save once to apply all settings. Without JavaScript, adding or removing a row reloads the form but does not save your changes.

| Field | Match type | Value |
| --- | --- | --- |
| Authors | Equals | `Sponsored Post` |
| Link | Contains | `/sponsored/` |
| Tags | Contains | `coupon` |
| Thumbnail URL | Contains | `coupon-thumbnail` |

Text matches ignore capitalisation by default, including multibyte text. Tick **Case-sensitive** to match the original case. Contains, equals, starts with and ends with treat values literally: `/sponsored/` is text, not a regex. Blank values are invalid; spaces in non-blank values are kept.

Choose **Regex** for a complete PHP expression with delimiters and optional flags, such as `/sponsored/i`. Its flags control capitalisation; the checkbox does not apply. Invalid expressions cannot be saved and never become literal matches.

Any matching additional rule blocks the article. The title whitelist cannot override it. If no additional rule matches, the existing title blacklist and whitelist still apply: blacklist matches block, and every whitelist rule must match. All rules share the **Mark as read** action and apply system-wide. Existing title settings are kept separately, without migration or changes to their matching behaviour.

### Choosing a field

- **Title** uses the parsed title supplied by FreshRSS.
- **Authors** and **Tags** match each name or item tag separately, never a joined string. Tags are article categories, not feed folders.
- **Link** and **Thumbnail URL** decode HTML entities but keep URL encoding, query strings and fragments. A missing link does not fall back to the GUID. Thumbnail rules use only the imported thumbnail, not body images or enclosure images.
- **GUID** matches the identifier as a string; it need not be a URL.

Content matching is not supported. Filtering does not parse article bodies or fetch extra content.

### Saving rules

If a rule is invalid, fix it and save again. Your saved settings stay unchanged.

To remove all additional rules, remove every row and save.

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
