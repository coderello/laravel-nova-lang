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

## Available Languages

Note: There is no need to update the count of translated strings and add your username below, as this is done by script when your PR is merged.

Total languages ![44](https://img.shields.io/badge/44-gray?style=flat-square)  
Total lines translated ![17,207 (92.2%)](https://img.shields.io/badge/17,207-92%25-yellow?style=flat-square)

| Code | Language | Translated files | Lines translated | Thanks to |
| --- | --- | --- | --- | --- |
| `en` | English | [`php`](resources/lang/en) [`json`](resources/lang/en.json) | ![424 (100%)](https://img.shields.io/badge/424-100%25-brightgreen?style=flat-square) | [taylorotwell](https://github.com/taylorotwell), [bonzai](https://github.com/bonzai), [davidhemphill](https://github.com/davidhemphill), [jbrooksuk](https://github.com/jbrooksuk), [themsaid](https://github.com/themsaid), [kitbs](https://github.com/kitbs), [dillingham](https://github.com/dillingham) |
| `fr` | French | [`php`](resources/lang/fr) [`json`](resources/lang/fr.json) | ![424 (100%)](https://img.shields.io/badge/424-100%25-brightgreen?style=flat-square) | [MarceauKa](https://github.com/MarceauKa), [Yannik-Slym](https://github.com/Yannik-Slym), [InfinityWebMe](https://github.com/InfinityWebMe), [kitbs](https://github.com/kitbs), [shaffe-fr](https://github.com/shaffe-fr), [Arryan](https://github.com/Arryan), [voidgraphics](https://github.com/voidgraphics), [KillianH](https://github.com/KillianH), [rbnhtl](https://github.com/rbnhtl), [stockhausen](https://github.com/stockhausen) |
| `ka` | Georgian | [`php`](resources/lang/ka) [`json`](resources/lang/ka.json) | ![424 (100%)](https://img.shields.io/badge/424-100%25-brightgreen?style=flat-square) | [akalongman](https://github.com/akalongman), [zgabievi](https://github.com/zgabievi) |
| `de` | German | [`php`](resources/lang/de) [`json`](resources/lang/de.json) | ![424 (100%)](https://img.shields.io/badge/424-100%25-brightgreen?style=flat-square) | [pille1842](https://github.com/pille1842), [shieraki](https://github.com/shieraki), [kitbs](https://github.com/kitbs), [tobiasthaden](https://github.com/tobiasthaden), [dakira](https://github.com/dakira) |
| `es` | Spanish | [`php`](resources/lang/es) [`json`](resources/lang/es.json) | ![424 (100%)](https://img.shields.io/badge/424-100%25-brightgreen?style=flat-square) | [kitbs](https://github.com/kitbs), [joebordes](https://github.com/joebordes), [ajmariduena](https://github.com/ajmariduena), [iksaku](https://github.com/iksaku), [IGedeon](https://github.com/IGedeon), [Arryan](https://github.com/Arryan), [kennyhorna](https://github.com/kennyhorna), [miktown](https://github.com/miktown), [xcodinas](https://github.com/xcodinas), [dgtal](https://github.com/dgtal), [rodrigore](https://github.com/rodrigore) |
| `ar` | Arabic | [`php`](resources/lang/ar) [`json`](resources/lang/ar.json) | ![420 (99.1%)](https://img.shields.io/badge/420-99%25-green?style=flat-square) | [saleem-hadad](https://github.com/saleem-hadad), [danyelkeddah](https://github.com/danyelkeddah), [omarfathy13](https://github.com/omarfathy13), [MohamedMaher5](https://github.com/MohamedMaher5), [CaddyDz](https://github.com/CaddyDz), [i3asm](https://github.com/i3asm), [Arryan](https://github.com/Arryan) |
| `pt‑BR` | Brazilian Portuguese | [`php`](resources/lang/pt-BR) [`json`](resources/lang/pt-BR.json) | ![420 (99.1%)](https://img.shields.io/badge/420-99%25-green?style=flat-square) | [henryavila](https://github.com/henryavila), [pedrofurtado](https://github.com/pedrofurtado), [eduardokum](https://github.com/eduardokum), [saulo-silva](https://github.com/saulo-silva), [chbbc](https://github.com/chbbc), [hpiaia](https://github.com/hpiaia), [IgorDePaula](https://github.com/IgorDePaula), [emtudo](https://github.com/emtudo) |
| `nl` | Dutch | [`php`](resources/lang/nl) [`json`](resources/lang/nl.json) | ![420 (99.1%)](https://img.shields.io/badge/420-99%25-green?style=flat-square) | [DannyvdSluijs](https://github.com/DannyvdSluijs), [happyDemon](https://github.com/happyDemon), [steefmin](https://github.com/steefmin), [jschram](https://github.com/jschram), [sebastiaanspeck](https://github.com/sebastiaanspeck), [preliot](https://github.com/preliot), [webovatenl](https://github.com/webovatenl), [daniel-de-wit](https://github.com/daniel-de-wit) |
| `it` | Italian | [`php`](resources/lang/it) [`json`](resources/lang/it.json) | ![420 (99.1%)](https://img.shields.io/badge/420-99%25-green?style=flat-square) | (unknown), [alfonsocuccaro](https://github.com/alfonsocuccaro), [f-liva](https://github.com/f-liva), [manuelcoppotelli](https://github.com/manuelcoppotelli), [trippo](https://github.com/trippo), [dejdav](https://github.com/dejdav) |
| `km` | Khmer | [`php`](resources/lang/km) [`json`](resources/lang/km.json) | ![420 (99.1%)](https://img.shields.io/badge/420-99%25-green?style=flat-square) | [chhaihongsrun](https://github.com/chhaihongsrun) |
| `ur` | Urdu | [`php`](resources/lang/ur) [`json`](resources/lang/ur.json) | ![420 (99.1%)](https://img.shields.io/badge/420-99%25-green?style=flat-square) | [junaidtariq48](https://github.com/junaidtariq48) |
| `cs` | Czech | [`php`](resources/lang/cs) [`json`](resources/lang/cs.json) | ![407 (96%)](https://img.shields.io/badge/407-96%25-green?style=flat-square) | [walaskir](https://github.com/walaskir), [theimerj](https://github.com/theimerj), [genesiscz](https://github.com/genesiscz), [walaski](https://github.com/walaski) |
| `hu` | Hungarian | [`php`](resources/lang/hu) [`json`](resources/lang/hu.json) | ![407 (96%)](https://img.shields.io/badge/407-96%25-green?style=flat-square) | [milli05](https://github.com/milli05), [bgeree](https://github.com/bgeree), [lintaba](https://github.com/lintaba) |
| `id` | Indonesian | [`php`](resources/lang/id) [`json`](resources/lang/id.json) | ![407 (96%)](https://img.shields.io/badge/407-96%25-green?style=flat-square) | [dvlwj](https://github.com/dvlwj), [opanegro](https://github.com/opanegro) |
| `ru` | Russian | [`php`](resources/lang/ru) [`json`](resources/lang/ru.json) | ![406 (95.8%)](https://img.shields.io/badge/406-95%25-green?style=flat-square) | [hivokas](https://github.com/hivokas), [sanasol](https://github.com/sanasol), [den1n](https://github.com/den1n), [deadem](https://github.com/deadem), [soulshockers](https://github.com/soulshockers), [estim](https://github.com/estim) |
| `ca` | Catalan | [`php`](resources/lang/ca) [`json`](resources/lang/ca.json) | ![405 (95.5%)](https://img.shields.io/badge/405-95%25-green?style=flat-square) | [joebordes](https://github.com/joebordes), [gerardnll](https://github.com/gerardnll) |
| `nb` | Norwegian Bokmål | [`php`](resources/lang/nb) [`json`](resources/lang/nb.json) | ![404 (95.3%)](https://img.shields.io/badge/404-95%25-green?style=flat-square) | [einar-johan](https://github.com/einar-johan) |
| `ro` | Romanian | [`php`](resources/lang/ro) [`json`](resources/lang/ro.json) | ![404 (95.3%)](https://img.shields.io/badge/404-95%25-green?style=flat-square) | [BTeodorWork](https://github.com/BTeodorWork), [dtix](https://github.com/dtix), [alexgiuvara](https://github.com/alexgiuvara) |
| `sv` | Swedish | [`php`](resources/lang/sv) [`json`](resources/lang/sv.json) | ![404 (95.3%)](https://img.shields.io/badge/404-95%25-green?style=flat-square) | [tanjemark](https://github.com/tanjemark), [slackernrrd](https://github.com/slackernrrd) |
| `tr` | Turkish | [`php`](resources/lang/tr) [`json`](resources/lang/tr.json) | ![404 (95.3%)](https://img.shields.io/badge/404-95%25-green?style=flat-square) | [jnbn](https://github.com/jnbn), [bureken](https://github.com/bureken), [Milkhan](https://github.com/Milkhan), [sineld](https://github.com/sineld), [semihkeskindev](https://github.com/semihkeskindev), [dilekuzulmez](https://github.com/dilekuzulmez) |
| `uk` | Ukrainian | [`php`](resources/lang/uk) [`json`](resources/lang/uk.json) | ![404 (95.3%)](https://img.shields.io/badge/404-95%25-green?style=flat-square) | [soulshockers](https://github.com/soulshockers), [Ostap34JS](https://github.com/Ostap34JS), [osbre](https://github.com/osbre) |
| `uz‑Latn` | Uzbek (Latin) | [`php`](resources/lang/uz-Latn) [`json`](resources/lang/uz-Latn.json) | ![404 (95.3%)](https://img.shields.io/badge/404-95%25-green?style=flat-square) | [shokhaa](https://github.com/shokhaa) |
| `fa` | Farsi | [`php`](resources/lang/fa) [`json`](resources/lang/fa.json) | ![402 (94.8%)](https://img.shields.io/badge/402-94%25-yellow?style=flat-square) | [alirezamirsepassi](https://github.com/alirezamirsepassi), [mziraki](https://github.com/mziraki), [zareismail](https://github.com/zareismail), [SadeghPM](https://github.com/SadeghPM) |
| `af` | Afrikaans | [`php`](resources/lang/af) [`json`](resources/lang/af.json) | ![399 (94.1%)](https://img.shields.io/badge/399-94%25-yellow?style=flat-square) | [medlion](https://github.com/medlion) |
| `bs` | Bosnian | [`php`](resources/lang/bs) [`json`](resources/lang/bs.json) | ![399 (94.1%)](https://img.shields.io/badge/399-94%25-yellow?style=flat-square) | [hajro92](https://github.com/hajro92) |
| `ja` | Japanese | [`php`](resources/lang/ja) [`json`](resources/lang/ja.json) | ![399 (94.1%)](https://img.shields.io/badge/399-94%25-yellow?style=flat-square) | [Tsumagari](https://github.com/Tsumagari), [storyn26383](https://github.com/storyn26383) |
| `tk` | Turkmen | [`php`](resources/lang/tk) [`json`](resources/lang/tk.json) | ![398 (93.9%)](https://img.shields.io/badge/398-93%25-yellow?style=flat-square) | [kakajansh](https://github.com/kakajansh) |
| `pl` | Polish | [`php`](resources/lang/pl) [`json`](resources/lang/pl.json) | ![396 (93.4%)](https://img.shields.io/badge/396-93%25-yellow?style=flat-square) | [pzmarzly](https://github.com/pzmarzly), [Strus](https://github.com/Strus), [marekfilip](https://github.com/marekfilip), [mslepko](https://github.com/mslepko), [wiktor-k](https://github.com/wiktor-k) |
| `zh‑CN` | Chinese (Simplified) | [`php`](resources/lang/zh-CN) [`json`](resources/lang/zh-CN.json) | ![392 (92.5%)](https://img.shields.io/badge/392-92%25-yellow?style=flat-square) | [jcc](https://github.com/jcc), [zacksleo](https://github.com/zacksleo), [masterwto](https://github.com/masterwto) |
| `zh‑TW` | Chinese (Traditional) | [`php`](resources/lang/zh-TW) [`json`](resources/lang/zh-TW.json) | ![392 (92.5%)](https://img.shields.io/badge/392-92%25-yellow?style=flat-square) | [CasperLaiTW](https://github.com/CasperLaiTW), [zacksleo](https://github.com/zacksleo), [storyn26383](https://github.com/storyn26383) |
| `pt` | Portuguese | [`php`](resources/lang/pt) [`json`](resources/lang/pt.json) | ![376 (88.7%)](https://img.shields.io/badge/376-88%25-orange?style=flat-square) | [Pedrocssg](https://github.com/Pedrocssg) |
| `da` | Danish | [`php`](resources/lang/da) [`json`](resources/lang/da.json) | ![355 (83.7%)](https://img.shields.io/badge/355-83%25-red?style=flat-square) | [olivernybroe](https://github.com/olivernybroe), [rugaard](https://github.com/rugaard), [peterchrjoergensen](https://github.com/peterchrjoergensen) |
| `eu` | Basque | [`php`](resources/lang/eu) [`json`](resources/lang/eu.json) | ![353 (83.3%)](https://img.shields.io/badge/353-83%25-red?style=flat-square) | [JonPaternain](https://github.com/JonPaternain) |
| `hr` | Croatian | [`php`](resources/lang/hr) [`json`](resources/lang/hr.json) | ![351 (82.8%)](https://img.shields.io/badge/351-82%25-red?style=flat-square) | [defart](https://github.com/defart), [walaski](https://github.com/walaski) |
| `fil` | Filipino | [`php`](resources/lang/fil) [`json`](resources/lang/fil.json) | ![351 (82.8%)](https://img.shields.io/badge/351-82%25-red?style=flat-square) | [granaderos](https://github.com/granaderos) |
| `fi` | Finnish | [`php`](resources/lang/fi) [`json`](resources/lang/fi.json) | ![351 (82.8%)](https://img.shields.io/badge/351-82%25-red?style=flat-square) | [Krisseck](https://github.com/Krisseck) |
| `hi` | Hindi | [`php`](resources/lang/hi) [`json`](resources/lang/hi.json) | ![351 (82.8%)](https://img.shields.io/badge/351-82%25-red?style=flat-square) | [bantya](https://github.com/bantya) |
| `sr` | Serbian (Cyrillic) | [`php`](resources/lang/sr) [`json`](resources/lang/sr.json) | ![351 (82.8%)](https://img.shields.io/badge/351-82%25-red?style=flat-square) | [marjanovicsteva](https://github.com/marjanovicsteva) |
| `sr‑Latn` | Serbian (Latin) | [`php`](resources/lang/sr-Latn) [`json`](resources/lang/sr-Latn.json) | ![351 (82.8%)](https://img.shields.io/badge/351-82%25-red?style=flat-square) | [marjanovicsteva](https://github.com/marjanovicsteva) |
| `sk` | Slovak | [`php`](resources/lang/sk) [`json`](resources/lang/sk.json) | ![351 (82.8%)](https://img.shields.io/badge/351-82%25-red?style=flat-square) | [hejty](https://github.com/hejty) |
| `sl` | Slovenian | [`php`](resources/lang/sl) [`json`](resources/lang/sl.json) | ![351 (82.8%)](https://img.shields.io/badge/351-82%25-red?style=flat-square) | [morpheus7CS](https://github.com/morpheus7CS) |
| `lt` | Lithuanian | [`php`](resources/lang/lt) [`json`](resources/lang/lt.json) | ![350 (82.5%)](https://img.shields.io/badge/350-82%25-red?style=flat-square) | [minved](https://github.com/minved) |
| `bg` | Bulgarian | [`php`](resources/lang/bg) [`json`](resources/lang/bg.json) | ![348 (82.1%)](https://img.shields.io/badge/348-82%25-red?style=flat-square) | [BKirev](https://github.com/BKirev) |
| `tl` | Tagalog | [`php`](resources/lang/tl) [`json`](resources/lang/tl.json) | ![344 (81.1%)](https://img.shields.io/badge/344-81%25-red?style=flat-square) | [rcjavier](https://github.com/rcjavier) |

## Missing Languages

The following languages are supported for the main Laravel framework by the excellent [laravel-lang/lang](https://github.com/laravel-lang/lang) package. We would love for our package to make these languages available for Nova as well. If you are able to contribute to any of these or other languages, please read our [contributing guidelines](CONTRIBUTING.md) and raise a PR.

Parity with `laravel-lang/lang` ![42/76 (55.3%)](https://img.shields.io/badge/42%2F76-55%25-red?style=flat-square)

| Code | Language | Lines translated |
| --- | --- | --- |
| `sq` | Albanian | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `hy` | Armenian | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `az` | Azerbaijani | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `bn` | Bangla | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `be` | Belarusian | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `zh‑HK` | Chinese (Hong Kong) | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `et` | Estonian | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `gl` | Galician | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `de‑CH` | German (Switzerland) | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `el` | Greek | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `he` | Hebrew | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `is` | Icelandic | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `kn` | Kannada | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `kk` | Kazakh | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `ko` | Korean | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `lv` | Latvian | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `mk` | Macedonian | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `ms` | Malay | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `mr` | Marathi | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `mn` | Mongolian | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `cnr` | Montenegrin | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `ne` | Nepali | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `nn` | Norwegian Nynorsk | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `oc` | Occitan | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `ps` | Pashto | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `sc` | Sardinian | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `si` | Sinhala | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `sw` | Swahili | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `tg` | Tajik | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `th` | Thai | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `ug` | Uyghur | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `uz‑Cyrl` | Uzbek (Cyrillic) | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `vi` | Vietnamese | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |
| `cy` | Welsh | ![0 (0%)](https://img.shields.io/badge/0-0%25-lightgray?style=flat-square) |