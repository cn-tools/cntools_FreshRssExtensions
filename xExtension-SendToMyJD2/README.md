# FreshRSS SendToMyJD2

This add on for FreshRSS send links to your myJDownloader2 instance you defined in the config.\
Required FreshRSS version at least v1.20

## Documentation

You can define several configurations line by line in a text field. The search is performed based on your configuration within a category or feed and, if necessary, combined with the defined RegEx check. The base URL of the feed entry is used as the link that is sent to jDownloader2 if the defined checks apply.

### Definations for configuration

Seperator chars: `=` and `;`. How to see in the section [Some examples](#L36)

There are some key chars available:

- `k` for key: Define which data should be checked.
  - `c` for category
  - `f` for feed
  - `*` (star) define that each new entry should be checked. In this case the parameter `r` will probably be required.
- `v` for value: Here you define the ID of the category or feed, depending on the definition of the key. If your key is the `*`, this param is ignored.

The following chars are optional:

- `r` for regex pattern: The data from this parameter is evaluated using the PHP function [`preg_match`](https://www.php.net/manual/en/function.preg-match). The pattern is applied to the title of the new feed entry to be checked.
- `p` for package name: This text is sent as package name to your jDownloader2. Default: `empty` - in this case jDownloader build the package name
- `m`: Mark the entry as readed if it was sent to myJD2. Allowed values: `0,1`. Default value: `0`
- `a` for autostart: Set to `1` if the added link is to be downloaded immediately. Allowed values: `0,1`. Default value: `0`

**The parameters `k`, `v` and `r` are checked with an AND operation!**

For the param `p` are 2 additional special words available:

- `{userid}`: Will be replaced into the user id of the current logged in user of FreshRSS
- `{date}Ymd{/date}`: The pattern inside the date content will be executed with the php function [`date`](https://www.php.net/manual/en/function.date.php)

### Some examples

```text
k=c;v=28;p=Category28_{date}Ydm{/date} by {userid};
k=f;v=79;r=/\bjohn\b/i;p=anytext {date}Ydm{/date} by {userid}
k=*;r=/\bfreshrss\b/i;p=anytext {date}Ydm{/date} by {userid};m=1;a=1
```

### How to find out the ID of category or feed?

Have a look to the extension [ShowFeedID](https://github.com/FreshRSS/Extensions/tree/master/xExtension-showFeedID).

## Translations

- English
- German

[![Crowdin](https://badges.crowdin.net/cntools-freshrssextensions/localized.svg)](https://crowdin.com/project/cntools-freshrssextensions)

You can help me to translate my extensions to a couple of languages on [Crowdin](https://crowdin.com/project/cntools-freshrssextensions). Or send me a new translation as pull request. I am happy to see!

## Special thanks

- [Anatoliy Kultenko](https://github.com/tofika) for [`my.jdownloader.org-api-php-class`](https://github.com/tofika/my.jdownloader.org-api-php-class)

## Installation

To install an extension, download the extension archive first and extract it on your PC.\
Then, upload the specific extension(s) you want on your server.

Extensions must be in the ./extensions directory of your FreshRSS installation.
