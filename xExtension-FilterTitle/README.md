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

Additional you can choose if the blocked entries not be added into database (default) or added into database but marked as read.

## Translations

- English
- German

[![Crowdin](https://badges.crowdin.net/cntools-freshrssextensions/localized.svg)](https://crowdin.com/project/cntools-freshrssextensions)

You can help me to translate my extensions to a couple of languages on [Crowdin](https://crowdin.com/project/cntools-freshrssextensions). Or send me a new translation as pull request. I am happy to see!

## Special Thanks

- base developed by [6david9](https://github.com/6david9) with [PR](https://github.com/cn-tools/cntools_FreshRssExtensions/pull/9)
- [lamyergeier](https://github.com/lamyergeier) for testing
