# FreshRSS FeedTitleBuilder
Build your own feed title based on url, the original feed title and the date the feed was added

## Documentation

This add on for FreshRSS change the title of the rss feed. It is working in the adding process only. After that process this add on have no effect.\
To design your preferred title as you like use the available special words.

### Available special words

| Word | Description |
| :---: | :--- |
| `{origtitle}` | Use it for the original title of the feed you will add |
| `{date}value{/date}` | Design a formatted date string as you like |
| `{url}value{/url}` | Access to some text sections of the full url of the feed |

### Date

The code use the function `date` of `php`. So there are all characters available which this function can use.\
Have a look for details: [PHP Date](https://www.php.net/manual/en/function.date.php)

### URL

The code use the function `parse_url` of `php`. Therefore you can use all words which are documented for it. Note that depending on the PHP version you are using, it may give different keywords.\
Have a look for details: [PHP parse_url](https://www.php.net/manual/en/function.parse-url.php)

**Probably the most needed words:**

| Word | Description |
| :---: | :--- |
| schema | You will get \'http\', \'https\', \'ftp\' or each other. Ex. \'https\'. |
| host | You will get your domain name including the TLD and if available \'www\'. |
| query | You will get the text between \'?\' and \'#\'. |
| fragment | You will get the text after \'#\'. |

**Additional special words by CN-Tools**

Maybe the word 'host' provides to much, so i offer the following extra words:

| Word | Description |
| :---: | :--- |
| hostsub | You will get the full text before the penultimate dot. |
| hostname | You will get the text between the penultimate and last dot. |
| hosttld | You will get the text after the last dot. |

### Example

`{url}hostname{/url}.{url}hosttld{/url} - {origtitle} (added {date}Ymd{/date})`

## Translations

- English
- German
- French

[![Crowdin](https://badges.crowdin.net/cntools-freshrssextensions/localized.svg)](https://crowdin.com/project/cntools-freshrssextensions)

You can help me to translate my extensions to a couple of languages on [Crowdin](https://crowdin.com/project/cntools-freshrssextensions). Or send me a new translation as pull request. I am happy to see!

## Special thanks

- [Damien Leroy](https://github.com/ShiiFu) for translation into french

## Installation

To install an extension, download the extension archive first and extract it on your PC.\
Then, upload the specific extension(s) you want on your server.

Extensions must be in the ./extensions directory of your FreshRSS installation.
