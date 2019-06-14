<p align="center"><img alt="Laraflash" src="logo.png" width="450"></p>

<p align="center">This package provides the language support for <b>Laravel Nova</b>.</p>

<p align="center">Feel free to submit your language or update an existing one by sending a PR to help other people.</p>

## Installation

```bash
composer require coderello/laravel-nova-lang
```

## Usage
### Publish Command
Publish translations for one language:
```bash
php artisan nova-lang:publish de
```

Publish translations for multiple languages:
```bash
php artisan nova-lang:publish de,ru
```

Publish translations for all languages:
```bash
php artisan nova-lang:publish --all
```

Publish translations and override existing files:
```bash
php artisan nova-lang:publish de,ru --force
```

### Development Commands (debug mode only)

You must have the `app.debug` config option set to true for these commands to be available:

#### Missing Command

This command is to assist contributors to find any untranslated keys for their chosen language. A stub JSON file will be created at `storage_path('app/nova-lang/missing/{locale}.json')`. You can copy those keys into the `resources/lang/{locale}.json` language file in your own fork of the repository, translate them and create a pull request.

Output missing translation keys for one or more languages:
```bash
php artisan nova-lang:missing de,ru
```

Output missing translation keys for all languages:
```bash
php artisan nova-lang:missing --all
```

#### Stats Command

This command is to assist maintainers to record the completeness of each language and contributors. A `README.excerpt.md` and `contributors.json` file will be created at `storage_path('app/nova-lang')`. You can copy those files into your own fork of the repository and create a pull request.

Output list of languages, lines translated and contributors:
```bash
php artisan nova-lang:stats
```

## Available Languages

| Language | Code | Lines translated | Thanks to |
| --- | --- | --- | --- |
| French | fr | 383 (99%) | [Arryan](https://github.com/Arryan), [MarceauKa](https://github.com/MarceauKa) |
| English | en | 374 (96.6%) | [taylorotwell](https://github.com/taylorotwell), [bonzai](https://github.com/bonzai), [davidhemphill](https://github.com/davidhemphill), [themsaid](https://github.com/themsaid) |
| Farsi | fa | 374 (96.6%) | [mziraki](https://github.com/mziraki) |
| Brazilian Portuguese | pt-BR | 372 (96.1%) | [chbbc](https://github.com/chbbc), [eduardokum](https://github.com/eduardokum) |
| Russian | ru | 366 (94.6%) | [Sanasol](https://github.com/Sanasol), [deadem](https://github.com/deadem), [estim](https://github.com/estim), [hivokas](https://github.com/hivokas) |
| German | de | 362 (93.5%) | [dakira](https://github.com/dakira), [pille1842](https://github.com/pille1842) |
| Spanish | es | 340 (87.9%) | [Arryan](https://github.com/Arryan), [ajmariduena](https://github.com/ajmariduena), [joebordes](https://github.com/joebordes), [rodrigore](https://github.com/rodrigore), [xcodinas](https://github.com/xcodinas) |
| Slovenian | sl | 338 (87.3%) | [morpheus7CS](https://github.com/morpheus7CS) |
| Bulgarian | bg | 338 (87.3%) | [BKirev](https://github.com/BKirev) |
| Croatian | hr | 338 (87.3%) | [walaski](https://github.com/walaski) |
| Danish | da | 338 (87.3%) | [olivernybroe](https://github.com/olivernybroe) |
| Catalan | ca | 338 (87.3%) | [joebordes](https://github.com/joebordes) |
| Lithuanian | lt | 338 (87.3%) | [minved](https://github.com/minved) |
| Finnish | fi | 338 (87.3%) | [Krisseck](https://github.com/Krisseck) |
| Basque | eu | 338 (87.3%) | [JonPaternain](https://github.com/JonPaternain) |
| Portuguese | pt | 338 (87.3%) | [Pedrocssg](https://github.com/Pedrocssg) |
| Filipino | fil | 338 (87.3%) | [granaderos](https://github.com/granaderos) |
| Serbian | sr | 338 (87.3%) | [marjanovicsteva](https://github.com/marjanovicsteva) |
| Hindi | hi | 338 (87.3%) | [bantya](https://github.com/bantya) |
| Italian | it | 338 (87.3%) | [dejdav](https://github.com/dejdav), [manuelcoppotelli](https://github.com/manuelcoppotelli) |
| Hungarian | hu | 338 (87.3%) | [milli05](https://github.com/milli05) |
| Indonesian | id | 338 (87.3%) | [dvlwj](https://github.com/dvlwj) |
| Swedish | sv | 338 (87.3%) | [tanjemark](https://github.com/tanjemark) |
| Georgian | ka | 338 (87.3%) | [akalongman](https://github.com/akalongman) |
| Arabic | ar | 338 (87.3%) | [Arryan](https://github.com/Arryan), [saleem-hadad](https://github.com/saleem-hadad) |
| Ukrainian | uk | 338 (87.3%) | [coderello](https://github.com/coderello) |
| Tagalog | tl | 338 (87.3%) | [rcjavier](https://github.com/rcjavier) |
| Slovak | sk | 337 (87.1%) | [hejty](https://github.com/hejty) |
| Chinese (Traditional) | zh-TW | 336 (86.8%) | [CasperLaiTW](https://github.com/CasperLaiTW) |
| Chinese | cn | 336 (86.8%) | [Pierolin](https://github.com/Pierolin) |
| Romanian | ro | 336 (86.8%) | [BTeodorWork](https://github.com/BTeodorWork), [alexgiuvara](https://github.com/alexgiuvara) |
| Chinese (Simplified) | zh-CN | 336 (86.8%) | [jcc](https://github.com/jcc) |
| Polish | pl | 336 (86.8%) | [Strus](https://github.com/Strus), [marekfilip](https://github.com/marekfilip), [wiktor-k](https://github.com/wiktor-k) |
| Czech | cs | 336 (86.8%) | [walaski](https://github.com/walaski) |
| Turkish | tr | 336 (86.8%) | [bureken](https://github.com/bureken), [dilekuzulmez](https://github.com/dilekuzulmez) |
| Dutch | nl | 336 (86.8%) | [happyDemon](https://github.com/happyDemon), [jschram](https://github.com/jschram), [sebastiaanspeck](https://github.com/sebastiaanspeck) |