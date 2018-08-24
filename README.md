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

## Status 
- English **[en]** - completed
- Russian **[ru]** - completed

