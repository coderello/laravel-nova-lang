<p align="center"><img alt="Laravel Nova Lang" src="https://coderello.com/images/packages/laravel-nova-lang.png" width="450"></p>

<p align="center">Language files for <b>Laravel Nova</b> translated into 40+ languages.</p>

This is not an official Laravel package, but is built from community contributions. If you are able to help by submitting a new language, reviewing an existing language, or adding missing keys, please read our [contributing guidelines](CONTRIBUTING.md) and raise a PR.

<hr>

## Installation

```bash
composer require coderello/laravel-nova-lang
```

## Usage
### Publish Command
* Publish translations for one language:
  ```bash
  php artisan nova-lang:publish de
  ```

* Publish translations for multiple languages (comma-separated):
  ```bash
  php artisan nova-lang:publish de,ru
  ```

* Publish translations for all available languages:
  ```bash
  php artisan nova-lang:publish --all
  ```

* Publish translations and override existing files:
  ```bash
  php artisan nova-lang:publish de,ru --force
  ```

#### Aliases
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

### Development Commands

Documentation of development commands for contributors is available at [Development Commands](development-commands.md).

### Available Languages

Total languages **44**  
Total lines translated **17,134 (91.8%)**

* `en` English &middot; **424 (100%)**
* `fr` French &middot; **424 (100%)**
* `de` German &middot; **424 (100%)**
* `es` Spanish &middot; **424 (100%)**
* `ar` Arabic &middot; **420 (99.1%)**
* `pt‑BR` Brazilian Portuguese &middot; **420 (99.1%)**
* `nl` Dutch &middot; **420 (99.1%)**
* `it` Italian &middot; **420 (99.1%)**
* `km` Khmer &middot; **420 (99.1%)**
* `ur` Urdu &middot; **420 (99.1%)**
* `cs` Czech &middot; **407 (96%)**
* `hu` Hungarian &middot; **407 (96%)**
* `id` Indonesian &middot; **407 (96%)**
* `ru` Russian &middot; **406 (95.8%)**
* `ca` Catalan &middot; **405 (95.5%)**
* `nb` Norwegian Bokmål &middot; **404 (95.3%)**
* `ro` Romanian &middot; **404 (95.3%)**
* `sv` Swedish &middot; **404 (95.3%)**
* `tr` Turkish &middot; **404 (95.3%)**
* `uk` Ukrainian &middot; **404 (95.3%)**
* `uz‑Latn` Uzbek (Latin) &middot; **404 (95.3%)**
* `fa` Farsi &middot; **402 (94.8%)**
* `af` Afrikaans &middot; **399 (94.1%)**
* `bs` Bosnian &middot; **399 (94.1%)**
* `ja` Japanese &middot; **399 (94.1%)**
* `tk` Turkmen &middot; **398 (93.9%)**
* `pl` Polish &middot; **396 (93.4%)**
* `zh‑CN` Chinese (Simplified) &middot; **392 (92.5%)**
* `zh‑TW` Chinese (Traditional) &middot; **392 (92.5%)**
* `pt` Portuguese &middot; **376 (88.7%)**
* `da` Danish &middot; **355 (83.7%)**
* `eu` Basque &middot; **353 (83.3%)**
* `hr` Croatian &middot; **351 (82.8%)**
* `fil` Filipino &middot; **351 (82.8%)**
* `fi` Finnish &middot; **351 (82.8%)**
* `ka` Georgian &middot; **351 (82.8%)**
* `hi` Hindi &middot; **351 (82.8%)**
* `sr` Serbian (Cyrillic) &middot; **351 (82.8%)**
* `sr‑Latn` Serbian (Latin) &middot; **351 (82.8%)**
* `sk` Slovak &middot; **351 (82.8%)**
* `sl` Slovenian &middot; **351 (82.8%)**
* `lt` Lithuanian &middot; **350 (82.5%)**
* `bg` Bulgarian &middot; **348 (82.1%)**
* `tl` Tagalog &middot; **344 (81.1%)**

See the full list of contributors on [GitHub](https://github.com/coderello/laravel-nova-lang#available-languages).