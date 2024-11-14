# FreshRSS YouTubeChannel2RssFeed

Transfer on the fly a YouTube URL into an RSS Feed.\
Required FreshRSS version at least v1.16

You can easily add Youtube video subscriptions by pasting URLs.

## Documentation

The base idea behind creating this extension was to easily convert YouTube URLs into RSS feeds.

You can easily add Youtube video subscriptions by pasting URLs like:

- `https://www.youtube.com/channel/UClvRFciRu04eKgti3g7qHUA`
- `https://www.youtube.com/user/YouTube`
- `https://www.youtube.com/playlist?list=PLcZ1vtdmuI2P7eZvbSq_d5mJXXywHtfyq`

### YouTube handles

If you like to add YouTube handles easily too, you must config a third party service URL. This must be an instance of [YouTube-operational-API](https://github.com/Benjamin-Loison/YouTube-operational-API). Please check the manufacturer's website of this third party software to see if a publicly accessible instance is provided. I rename it now YT-OAPI 😉.

YouTube handles URL look like:

- `https://www.youtube.com/@youtube`

If you don't want to use a publicly instance of YT-OAPI you can host your own instance. For example as [Docker container](https://hub.docker.com/r/benjaminloison/youtube-operational-api). For detail informations have a look in the documentation on manufacturer's website.

#### My prefered YAML code for a self hosted Docker container

```yaml
version: "3"
services:
  youtube-operational-api:
    image: benjaminloison/youtube-operational-api:latest
    restart: unless-stopped
    ports:
      - "<choose-your-own>:80"
```

**Attention:** This is a demo code only! You have to change it to your own preferences.

If you deploy FreshRSS and YT-OAPI within the same Docker compose file, the URL looks a little different. Therefore you have to use the given name of the service for YT-OAPI in combination with the port inside the container.

Based on the example above, you need to enter the following URL in the settings of this add-on:

```url
http://youtube-operational-api:80
```

### Detect YouTube shorts (by duration too)

In addition, YT-OAPI makes it possible to identify YouTube shorts. Therefore, there is an option in the settings to mark these YouTube shorts as readed when the RSS article is received, or not to save it in the database at all. In order to be able to track it, a log is written into FreshRSS LOG file in both of these cases.

There is also a setting to declare YouTube videos as short based on their duration.

#### Default configuration for YouTube shorts

Add YouTube shorts into database without any restcritions or changes.

## Translations

- English
- German

[![Crowdin](https://badges.crowdin.net/cntools-freshrssextensions/localized.svg)](https://crowdin.com/project/cntools-freshrssextensions)

You can help me to translate my extensions to a couple of languages on [Crowdin](https://crowdin.com/project/cntools-freshrssextensions). Or send me a new translation as pull request. I am happy to see!

## Installation

To install an extension, download the extension archive first and extract it on your PC. Then, upload the specific extension(s) you want on your server.

Extensions must be in the ./extensions directory of your FreshRSS installation.

## Change log

### v0.6.1-alpha (2024-05-29)

- Add an option to also interpret short YouTube videos as 'short'

### v0.6.0-alpha (2024-05-06)

- add option window
- add detection of YouTube handles
- add functionallity to detect YouTube shorts
- add option to mark shorts as read or block it

### v0.5.0 (2024-02-27)

- modify function call of php `version_compare`

### v0.4.0 (2023-12-02)

- Removed third party URL call from v0.0.3

### v0.3.0 (2022-02-11)

- Receive 50 instead of 15 items from YouTube by using third party service found on [stackoverflow.com](https://stackoverflow.com/questions/56430703/how-to-use-youtube-data-api-v3-to-get-more-than-15-videos-in-an-rss-reader-ne) by [Bman70](https://stackoverflow.com/users/7922428/bman70)

### v0.2.0 (2020-01-10)

- add support for playlist and user links

### v0.1.0 (2019-12-06)

- init version for YouTube channel links
