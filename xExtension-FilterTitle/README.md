# FreshRSS FilterTitle

A FreshRSS extension to filter feed entries by title.

# Documentation

Setup each keyword oneline by one.

```
some keyword
spam name
/\p{Latin}/i
other
```

Each pattern will checked as string or regex with 'preg_match'.

You can choose how the result of the check should be used:
* blocking
* release

# Translations

- English
- German

If your favorite language is not available, feel free to send a new translation as pull request. I am happy to see!

# Special Thanks
- base developed by [6david9](https://github.com/6david9) with [PR](https://github.com/cn-tools/cntools_FreshRssExtensions/pull/9)
