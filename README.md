## Laravel Nova Lang

This package provides the language support for **Laravel Nova**.

Feel free to submit your language or update an existing one by sending a PR to help other people.

## Installation

```bash
composer require coderello/laravel-nova-lang
```

## Usage

Publish translations for one language:
```bash
php artisan nova-lang:publish de
```

Publish translations for multiple languages:
```bash
php artisan nova-lang:publish de,ru
```

Publish translations with overriding of existing ones:
```bash
php artisan nova-lang:publish de,ru --force
```

## Available Languages

| Language | Code | Status | Thanks to |
| --- | --- | --- | --- |
| English | en | completed | [taylorotwell](https://github.com/taylorotwell) |
| Rusian | ru | completed | [hivokas](https://github.com/hivokas) |
| German | de | machine translation, needs verification | [hivokas](https://github.com/hivokas) |
| Dutch | nl | machine translation, needs verification | [hivokas](https://github.com/hivokas) |
| Turkish | tr | machine translation, needs verification | [hivokas](https://github.com/hivokas) |
| Ukrainian | uk | completed | [hivokas](https://github.com/hivokas) |
| Arabic | ar | machine translation, needs verification | [hivokas](https://github.com/hivokas) |
| Spanish | es | completed | [ajmariduena](https://github.com/ajmariduena) |
| French | fr | machine translation, needs verification | [hivokas](https://github.com/hivokas) |
| Polish | pl | machine translation, needs verification | [hivokas](https://github.com/hivokas) |
| Polish | pl | machine translation, needs verification | [hivokas](https://github.com/hivokas) |
| Chinese (Simplified) | zh-CN | completed | [jcc](https://github.com/jcc) |
| Romanian | ro | completed | [BTeodorWork](https://github.com/BTeodorWork) |
| Chinese | cn | completed | [Pierolin](https://github.com/Pierolin) |
| Chinese (Taiwan) | zh-TW | completed | [CasperLaiTW](https://github.com/CasperLaiTW) |
| Georgian | ka | completed | [akalongman](https://github.com/akalongman) |
