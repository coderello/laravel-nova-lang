---
title: Development commands
section: Contributing
weight: 700
featherIcon: terminal
---

You must have the `app.debug` config option set to true for these commands to be available:

## Missing Command

This command is to assist contributors to find any untranslated keys for their chosen language.

A stub JSON file will be created at `storage_path('app/nova-lang/missing/{locale}.json')`. You can copy those keys into the `resources/lang/{locale}.json` language file in your own fork of the repository, translate them and create a pull request.

* Output missing translation keys for one or more languages:
  ```bash
  php artisan nova-lang:missing de,ru
  ```

* Output missing translation keys for all languages:
  ```bash
  php artisan nova-lang:missing --all
  ```

## Reorder Command

This command is to assist contributors to ensure that the translation keys for their chosen language are in the same order as the `en.json` source file from Laravel Nova.

If you have imported the keys you are translating into a translation string management tool, the original order of the keys may be lost when you reexport the file. This makes the diff difficult to read because it will highlight all keys rather than just those which you have added or updated.

A new JSON file will be created at `storage_path('app/nova-lang/reorder/{locale}.json')`. You can copy the contents of this file to `resources/lang/{locale}.json` in your fork before you raise a PR.

* Output reordered translation keys for one or more languages:
  ```bash
  php artisan nova-lang:reorder de,ru
  ```

* Output reordered translation keys for all languages:
  ```bash
  php artisan nova-lang:reorder --all
  ```

## Country Names Command

This command is to assist contributors to download the country names automatically from the [Unicode Common Locale Data Repository](http://cldr.unicode.org/translation/displaynames/country-names) (CLDR). We use the CLDR as the definitive source for country names, as described in the contribution guidelines.

A new JSON file will be created at `storage_path('app/nova-lang/countries/{locale}.json')`. You can merge the keys from this file into `resources/lang/{locale}.json` in your fork before you raise a PR.

* Output country names for one or more languages:
```bash
php artisan nova-lang:country de,ru
```

* Output country names for all languages:
```bash
php artisan nova-lang:country --all
```

## Stats Command

This command is to assist maintainers to update the completeness of each language and list of contributors in this README file.

A `README.excerpt.md` and `contributors.json` file will be created at `storage_path('app/nova-lang')`. You can copy those files into your own fork of the repository and create a pull request.

* Output list of languages, lines translated and contributors:
  ```bash
  php artisan nova-lang:stats
  ```

Ensure you have created a GitHub personal access token and saved it as a `GITHUB_TOKEN_NOVALANG` env variable in your master Laravel application in order to download the most recent contributions.