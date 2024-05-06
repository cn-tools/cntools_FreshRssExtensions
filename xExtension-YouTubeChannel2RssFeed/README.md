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
- `https://www.youtube.com/@youtube` (by defining a third party service)

In order to convert "YouTube Handels" into an RSS feed, third-party software is required and this must be an instance of [YouTube-operational-API](https://github.com/Benjamin-Loison/YouTube-operational-API). Please check the manufacturer's website to see if a publicly accessible instance is provided. Otherwise for example, you can host your own instance using a [Docker container](https://hub.docker.com/r/benjaminloison/youtube-operational-api).

```yaml
version: "3"
services:
  youtube-operational-api:
    container_name: youtube-operational-api
    image: benjaminloison/youtube-operational-api:latest
    restart: unless-stopped
    ports:
      - "1234:80"
```

In addition, this third-party software makes it possible to identify YouTube shorts. Therefore, there is the option in the settings to mark these YouTube shorts as read when the RSS article is received or not to save it in the database at all. In order to be able to track it, a note is written to the FreshRSS LOG file in both of these cases.

## Translations

- English
- German

[![Crowdin](https://badges.crowdin.net/cntools-freshrssextensions/localized.svg)](https://crowdin.com/project/cntools-freshrssextensions)

You can help me to translate my extensions to a couple of languages on [Crowdin](https://crowdin.com/project/cntools-freshrssextensions). Or send me a new translation as pull request. I am happy to see!

## Installation

To install an extension, download the extension archive first and extract it on your PC. Then, upload the specific extension(s) you want on your server.

Extensions must be in the ./extensions directory of your FreshRSS installation.

## Change log

### v0.6.0-alpha (2024-05-06)

- add option window
- add detection of YouTube handels
- add functionallity to detect YouTube shorts
- add option to mark shorts as read or block it

### v0.0.5 (2024-02-27)

- modify function call of php `version_compare`

### v0.0.4 (2023-12-02)

- Removed third party url call from v0.0.3

### v0.0.3 (2022-02-11)

- Receive 50 instead of 15 items from YouTube by using third party service found on [stackoverflow.com](https://stackoverflow.com/questions/56430703/how-to-use-youtube-data-api-v3-to-get-more-than-15-videos-in-an-rss-reader-ne) by [Bman70](https://stackoverflow.com/users/7922428/bman70)

### v0.0.2 (2020-01-10)

- add support for playlist and user links

### v0.0.1 (2019-12-06)

- init version for YouTube channel links
