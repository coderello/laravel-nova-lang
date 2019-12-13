---
title: Publishing translations
section: Usage
weight: 800
featherIcon: play
---

- Publish translations for one language:
  ```bash
  php artisan nova-lang:publish de
  ```

- Publish translations for multiple languages (comma-separated):
  ```bash
  php artisan nova-lang:publish de,ru
  ```

- Publish translations for all available languages:
  ```bash
  php artisan nova-lang:publish --all
  ```

- Publish translations and override existing files:
  ```bash
  php artisan nova-lang:publish de,ru --force
  ```

## Aliases
The language codes chosen for the files in this repository may not match the preferences for your project. You can use the `‑‑alias` option to publish locales using a different filename.

* Publish translations for one language with an alias, using the simple format `{alias}`:
  ```bash
  php artisan nova-lang:publish de --alias=de-DE
  ```
  This will publish the file `de-DE.json`.

* Publish translations for multiple languages with multiple aliases, using the format `{locale}:{alias}` (comma-separated):
  ```bash
  php artisan nova-lang:publish de,ru,fr --alias=de:de-DE,ru:ru-RU
  ```
  This will publish the files `de-DE.json`, `ru-RU.json` and `fr.json` (no alias).

* Aliases can also be used with the `--all` flag:

  ```bash
  php artisan nova-lang:publish --all --alias=es:es-ES
  ```
  You do not need to supply an alias for every locale that is to be published, only those that you wish to override.

* Here are some example aliases for common use cases:

  * Use Chinese with scripts instead of regions: `zh-CN:zh-Hans,zh-TW:zh-Hant`
  * Default to Brazilian Portuguese over European: `pt:pt-PT,pt-BR:pt`
  * Default to Serbian in Latin script over Cyrillic: `sr-Latn:sr,sr:sr-Cyrl`


* There is also an `‑‑underscore` or `‑U` switch to publish locales with an underscore separator instead of a hyphen. This can be used in combination with aliases.