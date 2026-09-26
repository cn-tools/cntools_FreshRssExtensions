# YouTube API regression test

Requires PHP with cURL. From the repository root, start the local fixture server:

```sh
php -S 127.0.0.1:9091 tests/youtube-api-router.php
```

In another terminal, run:

```sh
php tests/youtube-api.php http://127.0.0.1:9091
```

Stop the fixture server afterward. The test exits nonzero on failure. It makes real
HTTP requests after disabling stream wrappers as FreshRSS 1.30 does, checks both
API call sites and filtering modes, and verifies redirects and HTTP errors.
FreshRSS context, logging, and entries are stubbed; this is not a full FreshRSS
installation test. `YOUTUBE_EXTENSION_FILE` can point to an unmodified extension
to demonstrate the original regression.
