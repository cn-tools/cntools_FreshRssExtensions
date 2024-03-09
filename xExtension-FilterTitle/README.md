# FreshRSS FilterTitle

A FreshRSS extension to filter feed entries by title.

## Documentation

You can specify whether the specified keywords should be used as a blacklist or as a whitelist.

Each line is checked individually. In addition to normal texts, RegEx expressions can also be defined. These must be able to be evaluated using the PHP function preg_match.

Examples:

```text
some keyword
spam name
/\p{Latin}/i
other
```

## Translations

- English
- German

If your favorite language is not available, feel free to send a new translation as pull request. I am happy to see!

## Special Thanks

- base developed by [6david9](https://github.com/6david9) with [PR](https://github.com/cn-tools/cntools_FreshRssExtensions/pull/9)
