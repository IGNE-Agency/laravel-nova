# Translations

## Nova in Dutch

The package ships Nova's texts in Dutch (`lang/nova/nl.json`), for both Nova's screens and its server-side messages. Every project shares this one translation instead of keeping its own copy.

The app's own `lang/vendor/nova/{locale}.json` still wins, key by key, so a project can override one text without copying the whole file. Nova is served in the user's preferred locale; see [Panel defaults](panel.md#locale).

A test checks the Dutch file against the English texts of the installed Nova version. When a Nova update adds texts, that test fails: add the new keys to `lang/nova/nl.json`.

## The package's own texts

The footer, the "Unknown" badge and the filter labels are in `lang/{en,nl}/*.php` under the `igne-nova` namespace. Publish them to change them:

```bash
php artisan vendor:publish --tag=igne-nova-lang
```
