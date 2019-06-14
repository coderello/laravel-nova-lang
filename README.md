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
| French | [fr](resources/lang/fr.json) | 383 (99%) | [Arryan](https://github.com/Arryan), [MarceauKa](https://github.com/MarceauKa) |
| Farsi | [fa](resources/lang/fa.json) | 374 (96.6%) | [mziraki](https://github.com/mziraki) |
| English | [en](resources/lang/en.json) | 374 (96.6%) | [taylorotwell](https://github.com/taylorotwell), [bonzai](https://github.com/bonzai), [davidhemphill](https://github.com/davidhemphill), [themsaid](https://github.com/themsaid) |
| Brazilian Portuguese | [pt-BR](resources/lang/pt-BR.json) | 372 (96.1%) | [chbbc](https://github.com/chbbc), [eduardokum](https://github.com/eduardokum) |
| Russian | [ru](resources/lang/ru.json) | 366 (94.6%) | [Sanasol](https://github.com/Sanasol), [deadem](https://github.com/deadem), [estim](https://github.com/estim), [hivokas](https://github.com/hivokas) |
| German | [de](resources/lang/de.json) | 362 (93.5%) | [dakira](https://github.com/dakira), [pille1842](https://github.com/pille1842) |
| Spanish | [es](resources/lang/es.json) | 340 (87.9%) | [Arryan](https://github.com/Arryan), [ajmariduena](https://github.com/ajmariduena), [joebordes](https://github.com/joebordes), [rodrigore](https://github.com/rodrigore), [xcodinas](https://github.com/xcodinas) |
| Serbian | [sr](resources/lang/sr.json) | 338 (87.3%) | [marjanovicsteva](https://github.com/marjanovicsteva) |
| Tagalog | [tl](resources/lang/tl.json) | 338 (87.3%) | [rcjavier](https://github.com/rcjavier) |
| Ukrainian | [uk](resources/lang/uk.json) | 338 (87.3%) | [coderello](https://github.com/coderello) |
| Arabic | [ar](resources/lang/ar.json) | 338 (87.3%) | [Arryan](https://github.com/Arryan), [saleem-hadad](https://github.com/saleem-hadad) |
| Georgian | [ka](resources/lang/ka.json) | 338 (87.3%) | [akalongman](https://github.com/akalongman) |
| Swedish | [sv](resources/lang/sv.json) | 338 (87.3%) | [tanjemark](https://github.com/tanjemark) |
| Indonesian | [id](resources/lang/id.json) | 338 (87.3%) | [dvlwj](https://github.com/dvlwj) |
| Hungarian | [hu](resources/lang/hu.json) | 338 (87.3%) | [milli05](https://github.com/milli05) |
| Italian | [it](resources/lang/it.json) | 338 (87.3%) | [dejdav](https://github.com/dejdav), [manuelcoppotelli](https://github.com/manuelcoppotelli) |
| Hindi | [hi](resources/lang/hi.json) | 338 (87.3%) | [bantya](https://github.com/bantya) |
| Filipino | [fil](resources/lang/fil.json) | 338 (87.3%) | [granaderos](https://github.com/granaderos) |
| Danish | [da](resources/lang/da.json) | 338 (87.3%) | [olivernybroe](https://github.com/olivernybroe) |
| Portuguese | [pt](resources/lang/pt.json) | 338 (87.3%) | [Pedrocssg](https://github.com/Pedrocssg) |
| Bulgarian | [bg](resources/lang/bg.json) | 338 (87.3%) | [BKirev](https://github.com/BKirev) |
| Croatian | [hr](resources/lang/hr.json) | 338 (87.3%) | [walaski](https://github.com/walaski) |
| Slovenian | [sl](resources/lang/sl.json) | 338 (87.3%) | [morpheus7CS](https://github.com/morpheus7CS) |
| Catalan | [ca](resources/lang/ca.json) | 338 (87.3%) | [joebordes](https://github.com/joebordes) |
| Lithuanian | [lt](resources/lang/lt.json) | 338 (87.3%) | [minved](https://github.com/minved) |
| Finnish | [fi](resources/lang/fi.json) | 338 (87.3%) | [Krisseck](https://github.com/Krisseck) |
| Basque | [eu](resources/lang/eu.json) | 338 (87.3%) | [JonPaternain](https://github.com/JonPaternain) |
| Slovak | [sk](resources/lang/sk.json) | 337 (87.1%) | [hejty](https://github.com/hejty) |
| Chinese (Traditional) | [zh-TW](resources/lang/zh-TW.json) | 336 (86.8%) | [CasperLaiTW](https://github.com/CasperLaiTW) |
| Chinese | [cn](resources/lang/cn.json) | 336 (86.8%) | [Pierolin](https://github.com/Pierolin) |
| Romanian | [ro](resources/lang/ro.json) | 336 (86.8%) | [BTeodorWork](https://github.com/BTeodorWork), [alexgiuvara](https://github.com/alexgiuvara) |
| Chinese (Simplified) | [zh-CN](resources/lang/zh-CN.json) | 336 (86.8%) | [jcc](https://github.com/jcc) |
| Polish | [pl](resources/lang/pl.json) | 336 (86.8%) | [Strus](https://github.com/Strus), [marekfilip](https://github.com/marekfilip), [wiktor-k](https://github.com/wiktor-k) |
| Czech | [cs](resources/lang/cs.json) | 336 (86.8%) | [walaski](https://github.com/walaski) |
| Turkish | [tr](resources/lang/tr.json) | 336 (86.8%) | [bureken](https://github.com/bureken), [dilekuzulmez](https://github.com/dilekuzulmez) |
| Dutch | [nl](resources/lang/nl.json) | 336 (86.8%) | [happyDemon](https://github.com/happyDemon), [jschram](https://github.com/jschram), [sebastiaanspeck](https://github.com/sebastiaanspeck) |