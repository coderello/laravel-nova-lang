# Laravel Nova Lang

<p align="center">Language files for <b>Laravel Nova</b> translated into 40+ languages.</p>

This is not an official Laravel package, but is built from community contributions. If you are able to help by submitting a new language, reviewing an existing language, or adding missing keys, please read our [contributing guidelines](CONTRIBUTING.md) and raise a PR.

<hr>

## Installation

```bash
composer require coderello/laravel-nova-lang
```

## Usage
### Publish Command
##### Publish translations for one language:
```bash
php artisan nova-lang:publish de
```

##### Publish translations for multiple languages (comma-separated):
```bash
php artisan nova-lang:publish de,ru
```

##### Publish translations for all available languages:
```bash
php artisan nova-lang:publish --all
```

##### Publish translations and override existing files:
```bash
php artisan nova-lang:publish de,ru --force
```

#### Aliases
The language codes chosen for the files in this repository may not match the preferences for your project. You can use the `‑‑alias` option to publish locales using a different filename.

##### Publish translations for one language with an alias, using the simple format `{alias}`:
```bash
php artisan nova-lang:publish de --alias=de-DE
```

This will publish the file `de-DE.json`.

##### Publish translations for multiple languages with multiple aliases, using the format `{locale}:{alias}` (comma-separated):
```bash
php artisan nova-lang:publish de,ru,fr --alias=de:de-DE,ru:ru-RU
```

This will publish the files `de-DE.json`, `ru-RU.json` and `fr.json` (no alias).

##### Aliases can also be used with the `--all` flag:

```bash
php artisan nova-lang:publish --all --alias=es:es-ES
```

You do not need to supply an alias for every locale that is to be published, only those that you wish to override.

Here are some example aliases for common use cases:

* Use Chinese with scripts instead of regions: `zh-CN:zh-Hans,zh-TW:zh-Hant`
* Default to Brazilian Portuguese over European: `pt:pt-PT,pt-BR:pt`
* Default to Serbian in Latin script over Cyrillic: `sr-Latn:sr,sr:sr-Cyrl`


There is also an `--underscore` or `-U` switch to publish locales with an underscore separator instead of a hyphen. This can be used in combination with aliases.

### Development Commands

Documentation of development commands for contributors and maintainers is available at [Development Commands](development-commands.md).

## Available Languages

We welcome new languages and additions/improvements to existing languages! Please read our [contributing guidelines](CONTRIBUTING.md) and raise a PR.

**Note**: There is no need to update the count of translated strings and add your username below, as this is done by script when your PR is merged.

Latest Nova version ![4.23.0](https://img.shields.io/badge/4.23.0-gray?style=flat-square)  
Total languages ![46](https://img.shields.io/badge/46-gray?style=flat-square)  
Total lines translated ![18,543 (87.1%)](https://img.shields.io/badge/18543-87%25-orange?style=flat-square)

| Code | Language | Translated files | Lines translated | Thanks to |
| --- | --- | --- | --- | --- |
| `en` | English | [`php`](resources/lang/en) [`json`](resources/lang/en.json) | ![463 (100%)](https://img.shields.io/badge/463-100%25-brightgreen?style=flat-square) | [taylorotwell](https://github.com/taylorotwell), [bonzai](https://github.com/bonzai), [davidhemphill](https://github.com/davidhemphill), [jbrooksuk](https://github.com/jbrooksuk), [themsaid](https://github.com/themsaid), [kitbs](https://github.com/kitbs), [dillingham](https://github.com/dillingham) |
| `fr` | French | [`php`](resources/lang/fr) [`json`](resources/lang/fr.json) | ![463 (100%)](https://img.shields.io/badge/463-100%25-brightgreen?style=flat-square) | [MarceauKa](https://github.com/MarceauKa), [shaffe-fr](https://github.com/shaffe-fr), [Yannik-Slym](https://github.com/Yannik-Slym), [InfinityWebMe](https://github.com/InfinityWebMe), [kitbs](https://github.com/kitbs), [Arryan](https://github.com/Arryan), [voidgraphics](https://github.com/voidgraphics), [KillianH](https://github.com/KillianH), [rbnhtl](https://github.com/rbnhtl), [stockhausen](https://github.com/stockhausen) |
| `de` | German | [`php`](resources/lang/de) [`json`](resources/lang/de.json) | ![463 (100%)](https://img.shields.io/badge/463-100%25-brightgreen?style=flat-square) | [pille1842](https://github.com/pille1842), [kitbs](https://github.com/kitbs), [shieraki](https://github.com/shieraki), [StanBarrows](https://github.com/StanBarrows), [tobiasthaden](https://github.com/tobiasthaden), [dakira](https://github.com/dakira) |
| `lv` | Latvian | [`php`](resources/lang/lv) [`json`](resources/lang/lv.json) | ![463 (100%)](https://img.shields.io/badge/463-100%25-brightgreen?style=flat-square) |  |
| `es` | Spanish | [`php`](resources/lang/es) [`json`](resources/lang/es.json) | ![463 (100%)](https://img.shields.io/badge/463-100%25-brightgreen?style=flat-square) | [kitbs](https://github.com/kitbs), [joebordes](https://github.com/joebordes), [ajmariduena](https://github.com/ajmariduena), [iksaku](https://github.com/iksaku), [IGedeon](https://github.com/IGedeon), [SpiritSaint](https://github.com/SpiritSaint), [alejandrotrevi](https://github.com/alejandrotrevi), [Arryan](https://github.com/Arryan), [kennyhorna](https://github.com/kennyhorna), [miktown](https://github.com/miktown), [xcodinas](https://github.com/xcodinas), [AndresReyesDev](https://github.com/AndresReyesDev), [Vitorinox](https://github.com/Vitorinox), [dgtal](https://github.com/dgtal), [rodrigore](https://github.com/rodrigore) |
| `zh‑CN` | Chinese (Simplified) | [`php`](resources/lang/zh-CN) [`json`](resources/lang/zh-CN.json) | ![459 (99.1%)](https://img.shields.io/badge/459-99%25-green?style=flat-square) | [jcc](https://github.com/jcc), [zacksleo](https://github.com/zacksleo), [masterwto](https://github.com/masterwto) |
| `zh‑TW` | Chinese (Traditional) | [`php`](resources/lang/zh-TW) [`json`](resources/lang/zh-TW.json) | ![456 (98.5%)](https://img.shields.io/badge/456-98%25-green?style=flat-square) | [CasperLaiTW](https://github.com/CasperLaiTW), [zacksleo](https://github.com/zacksleo), [storyn26383](https://github.com/storyn26383) |
| `ru` | Russian | [`php`](resources/lang/ru) [`json`](resources/lang/ru.json) | ![454 (98.1%)](https://img.shields.io/badge/454-98%25-green?style=flat-square) | [hivokas](https://github.com/hivokas), [sanasol](https://github.com/sanasol), [makhsam](https://github.com/makhsam), [den1n](https://github.com/den1n), [medvinator](https://github.com/medvinator), [deadem](https://github.com/deadem), [soulshockers](https://github.com/soulshockers), [kongulov](https://github.com/kongulov), [saundefined](https://github.com/saundefined), [ankkk](https://github.com/ankkk), [estim](https://github.com/estim) |
| `uz‑Latn` | Uzbek (Latin) | [`php`](resources/lang/uz-Latn) [`json`](resources/lang/uz-Latn.json) | ![448 (96.8%)](https://img.shields.io/badge/448-96%25-green?style=flat-square) | [shokhaa](https://github.com/shokhaa), [professor93](https://github.com/professor93) |
| `az` | Azerbaijani | [`php`](resources/lang/az) [`json`](resources/lang/az.json) | ![444 (95.9%)](https://img.shields.io/badge/444-95%25-green?style=flat-square) | [kongulov](https://github.com/kongulov) |
| `nl` | Dutch | [`php`](resources/lang/nl) [`json`](resources/lang/nl.json) | ![443 (95.7%)](https://img.shields.io/badge/443-95%25-green?style=flat-square) | [DannyvdSluijs](https://github.com/DannyvdSluijs), [happyDemon](https://github.com/happyDemon), [steefmin](https://github.com/steefmin), [jschram](https://github.com/jschram), [sebastiaanspeck](https://github.com/sebastiaanspeck), [preliot](https://github.com/preliot), [webovatenl](https://github.com/webovatenl), [daniel-de-wit](https://github.com/daniel-de-wit) |
| `pt‑BR` | Brazilian Portuguese | [`php`](resources/lang/pt-BR) [`json`](resources/lang/pt-BR.json) | ![440 (95%)](https://img.shields.io/badge/440-95%25-green?style=flat-square) | [henryavila](https://github.com/henryavila), [pedrofurtado](https://github.com/pedrofurtado), [eduardokum](https://github.com/eduardokum), [saulo-silva](https://github.com/saulo-silva), [chbbc](https://github.com/chbbc), [ranierif](https://github.com/ranierif), [hpiaia](https://github.com/hpiaia), [IgorDePaula](https://github.com/IgorDePaula), [emtudo](https://github.com/emtudo) |
| `id` | Indonesian | [`php`](resources/lang/id) [`json`](resources/lang/id.json) | ![423 (91.4%)](https://img.shields.io/badge/423-91%25-yellow?style=flat-square) | [dvlwj](https://github.com/dvlwj), [opanegro](https://github.com/opanegro), [Kristories](https://github.com/Kristories), [ezhasyafaat](https://github.com/ezhasyafaat) |
| `tr` | Turkish | [`php`](resources/lang/tr) [`json`](resources/lang/tr.json) | ![423 (91.4%)](https://img.shields.io/badge/423-91%25-yellow?style=flat-square) | [jnbn](https://github.com/jnbn), [bureken](https://github.com/bureken), [Milkhan](https://github.com/Milkhan), [erayusta](https://github.com/erayusta), [sineld](https://github.com/sineld), [semihkeskindev](https://github.com/semihkeskindev), [dilekuzulmez](https://github.com/dilekuzulmez), [suleymanozev](https://github.com/suleymanozev) |
| `tk` | Turkmen | [`php`](resources/lang/tk) [`json`](resources/lang/tk.json) | ![422 (91.1%)](https://img.shields.io/badge/422-91%25-yellow?style=flat-square) | [kakajansh](https://github.com/kakajansh) |
| `ka` | Georgian | [`php`](resources/lang/ka) [`json`](resources/lang/ka.json) | ![421 (90.9%)](https://img.shields.io/badge/421-90%25-yellow?style=flat-square) | [akalongman](https://github.com/akalongman), [zgabievi](https://github.com/zgabievi) |
| `ro` | Romanian | [`php`](resources/lang/ro) [`json`](resources/lang/ro.json) | ![421 (90.9%)](https://img.shields.io/badge/421-90%25-yellow?style=flat-square) | [BTeodorWork](https://github.com/BTeodorWork), [dtix](https://github.com/dtix), [alexgiuvara](https://github.com/alexgiuvara) |
| `ar` | Arabic | [`php`](resources/lang/ar) [`json`](resources/lang/ar.json) | ![417 (90.1%)](https://img.shields.io/badge/417-90%25-yellow?style=flat-square) | [saleem-hadad](https://github.com/saleem-hadad), [danyelkeddah](https://github.com/danyelkeddah), [omarfathy13](https://github.com/omarfathy13), [MohamedMaher5](https://github.com/MohamedMaher5), [CaddyDz](https://github.com/CaddyDz), [i3asm](https://github.com/i3asm), [moedayraki](https://github.com/moedayraki), [Arryan](https://github.com/Arryan) |
| `it` | Italian | [`php`](resources/lang/it) [`json`](resources/lang/it.json) | ![417 (90.1%)](https://img.shields.io/badge/417-90%25-yellow?style=flat-square) | (unknown), [alfonsocuccaro](https://github.com/alfonsocuccaro), [f-liva](https://github.com/f-liva), [manuelcoppotelli](https://github.com/manuelcoppotelli), [trippo](https://github.com/trippo), [dejdav](https://github.com/dejdav) |
| `km` | Khmer | [`php`](resources/lang/km) [`json`](resources/lang/km.json) | ![417 (90.1%)](https://img.shields.io/badge/417-90%25-yellow?style=flat-square) | [chhaihongsrun](https://github.com/chhaihongsrun) |
| `ur` | Urdu | [`php`](resources/lang/ur) [`json`](resources/lang/ur.json) | ![417 (90.1%)](https://img.shields.io/badge/417-90%25-yellow?style=flat-square) | [junaidtariq48](https://github.com/junaidtariq48) |
| `hu` | Hungarian | [`php`](resources/lang/hu) [`json`](resources/lang/hu.json) | ![404 (87.3%)](https://img.shields.io/badge/404-87%25-orange?style=flat-square) | [milli05](https://github.com/milli05), [bgeree](https://github.com/bgeree), [lintaba](https://github.com/lintaba) |
| `cs` | Czech | [`php`](resources/lang/cs) [`json`](resources/lang/cs.json) | ![403 (87%)](https://img.shields.io/badge/403-87%25-orange?style=flat-square) | [walaskir](https://github.com/walaskir), [theimerj](https://github.com/theimerj), [genesiscz](https://github.com/genesiscz), [walaski](https://github.com/walaski) |
| `ca` | Catalan | [`php`](resources/lang/ca) [`json`](resources/lang/ca.json) | ![402 (86.8%)](https://img.shields.io/badge/402-86%25-orange?style=flat-square) | [joebordes](https://github.com/joebordes), [gerardnll](https://github.com/gerardnll) |
| `af` | Afrikaans | [`php`](resources/lang/af) [`json`](resources/lang/af.json) | ![401 (86.6%)](https://img.shields.io/badge/401-86%25-orange?style=flat-square) | [medlion](https://github.com/medlion) |
| `bs` | Bosnian | [`php`](resources/lang/bs) [`json`](resources/lang/bs.json) | ![401 (86.6%)](https://img.shields.io/badge/401-86%25-orange?style=flat-square) | [hajro92](https://github.com/hajro92) |
| `ja` | Japanese | [`php`](resources/lang/ja) [`json`](resources/lang/ja.json) | ![401 (86.6%)](https://img.shields.io/badge/401-86%25-orange?style=flat-square) | [Tsumagari](https://github.com/Tsumagari), [storyn26383](https://github.com/storyn26383) |
| `nb` | Norwegian Bokmål | [`php`](resources/lang/nb) [`json`](resources/lang/nb.json) | ![401 (86.6%)](https://img.shields.io/badge/401-86%25-orange?style=flat-square) | [einar-johan](https://github.com/einar-johan) |
| `sv` | Swedish | [`php`](resources/lang/sv) [`json`](resources/lang/sv.json) | ![401 (86.6%)](https://img.shields.io/badge/401-86%25-orange?style=flat-square) | [tanjemark](https://github.com/tanjemark), [slackernrrd](https://github.com/slackernrrd) |
| `uk` | Ukrainian | [`php`](resources/lang/uk) [`json`](resources/lang/uk.json) | ![401 (86.6%)](https://img.shields.io/badge/401-86%25-orange?style=flat-square) | [soulshockers](https://github.com/soulshockers), [Ostap34JS](https://github.com/Ostap34JS), [osbre](https://github.com/osbre) |
| `fa` | Farsi | [`php`](resources/lang/fa) [`json`](resources/lang/fa.json) | ![399 (86.2%)](https://img.shields.io/badge/399-86%25-orange?style=flat-square) | [alirezamirsepassi](https://github.com/alirezamirsepassi), [mziraki](https://github.com/mziraki), [zareismail](https://github.com/zareismail), [SadeghPM](https://github.com/SadeghPM) |
| `pl` | Polish | [`php`](resources/lang/pl) [`json`](resources/lang/pl.json) | ![393 (84.9%)](https://img.shields.io/badge/393-84%25-red?style=flat-square) | [pzmarzly](https://github.com/pzmarzly), [Strus](https://github.com/Strus), [marekfilip](https://github.com/marekfilip), [mslepko](https://github.com/mslepko), [wiktor-k](https://github.com/wiktor-k) |
| `pt` | Portuguese | [`php`](resources/lang/pt) [`json`](resources/lang/pt.json) | ![380 (82.1%)](https://img.shields.io/badge/380-82%25-red?style=flat-square) | [Pedrocssg](https://github.com/Pedrocssg) |
| `da` | Danish | [`php`](resources/lang/da) [`json`](resources/lang/da.json) | ![352 (76%)](https://img.shields.io/badge/352-76%25-red?style=flat-square) | [olivernybroe](https://github.com/olivernybroe), [rugaard](https://github.com/rugaard), [peterchrjoergensen](https://github.com/peterchrjoergensen) |
| `eu` | Basque | [`php`](resources/lang/eu) [`json`](resources/lang/eu.json) | ![350 (75.6%)](https://img.shields.io/badge/350-75%25-red?style=flat-square) | [JonPaternain](https://github.com/JonPaternain) |
| `hr` | Croatian | [`php`](resources/lang/hr) [`json`](resources/lang/hr.json) | ![348 (75.2%)](https://img.shields.io/badge/348-75%25-red?style=flat-square) | [defart](https://github.com/defart), [walaski](https://github.com/walaski) |
| `fil` | Filipino | [`php`](resources/lang/fil) [`json`](resources/lang/fil.json) | ![348 (75.2%)](https://img.shields.io/badge/348-75%25-red?style=flat-square) | [granaderos](https://github.com/granaderos) |
| `fi` | Finnish | [`php`](resources/lang/fi) [`json`](resources/lang/fi.json) | ![348 (75.2%)](https://img.shields.io/badge/348-75%25-red?style=flat-square) | [Krisseck](https://github.com/Krisseck) |
| `hi` | Hindi | [`php`](resources/lang/hi) [`json`](resources/lang/hi.json) | ![348 (75.2%)](https://img.shields.io/badge/348-75%25-red?style=flat-square) | [bantya](https://github.com/bantya) |
| `sr` | Serbian (Cyrillic) | [`php`](resources/lang/sr) [`json`](resources/lang/sr.json) | ![348 (75.2%)](https://img.shields.io/badge/348-75%25-red?style=flat-square) | [marjanovicsteva](https://github.com/marjanovicsteva) |
| `sr‑Latn` | Serbian (Latin) | [`php`](resources/lang/sr-Latn) [`json`](resources/lang/sr-Latn.json) | ![348 (75.2%)](https://img.shields.io/badge/348-75%25-red?style=flat-square) | [marjanovicsteva](https://github.com/marjanovicsteva) |
| `sk` | Slovak | [`php`](resources/lang/sk) [`json`](resources/lang/sk.json) | ![348 (75.2%)](https://img.shields.io/badge/348-75%25-red?style=flat-square) | [hejty](https://github.com/hejty) |
| `sl` | Slovenian | [`php`](resources/lang/sl) [`json`](resources/lang/sl.json) | ![348 (75.2%)](https://img.shields.io/badge/348-75%25-red?style=flat-square) | [morpheus7CS](https://github.com/morpheus7CS) |
| `lt` | Lithuanian | [`php`](resources/lang/lt) [`json`](resources/lang/lt.json) | ![347 (74.9%)](https://img.shields.io/badge/347-74%25-red?style=flat-square) | [minved](https://github.com/minved) |
| `bg` | Bulgarian | [`php`](resources/lang/bg) [`json`](resources/lang/bg.json) | ![345 (74.5%)](https://img.shields.io/badge/345-74%25-red?style=flat-square) | [BKirev](https://github.com/BKirev) |
| `tl` | Tagalog | [`php`](resources/lang/tl) [`json`](resources/lang/tl.json) | ![341 (73.7%)](https://img.shields.io/badge/341-73%25-red?style=flat-square) | [rcjavier](https://github.com/rcjavier) |
