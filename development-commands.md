<p align="center"><img alt="Laravel Nova Lang" src="https://coderello.com/images/packages/laravel-nova-lang.png" width="450"></p>

<p align="center">Language files for <b>Laravel Nova</b> translated into 40+ languages.</p>

This is not an official Laravel package, but is built from community contributions. If you are able to help by submitting a new language, reviewing an existing language, or adding missing keys, please read our [contributing guidelines](CONTRIBUTING.md) and raise a PR.

<hr>

### Development Commands

These commands should only be run in your own local fork of the repository. They should not be run from within a Laravel application where this package is installed.

Clone the repository into a standalone directory and run `composer install` before running any of the below commands.

#### Missing Command

This command is to assist contributors to find any untranslated keys for their chosen language.

Any keys which are untranslated in the target language will be added to the file with the placeholder message "&lt;MISSING&gt;", ready for you to translate. Any "&lt;MISSING&gt;" values which you have not translated must be removed before you raise your pull request.

##### Insert missing translation keys for one or more languages:
```bash
php nova-lang missing de,ru
```

##### Insert missing translation keys for all languages:
```bash
php nova-lang missing --all
```

#### Reorder Command

This command is to assist contributors to ensure that the translation keys for their chosen language are in the same order as the `en.json` source file from Laravel Nova.

If you have imported the keys you are translating into a translation string management tool, the original order of the keys may be lost when you reexport the file. This makes the diff difficult to read because it will highlight all keys rather than just those which you have added or updated.

The language file will be updated with the correct key order. You should then commit the changes and raise a pull request.

##### Reorder translation keys for one or more languages:
```bash
php nova-lang reorder de,ru
```

##### Reorder translation keys for all languages:
```bash
php nova-lang reorder --all
```

#### Country Names Command

This command is to assist contributors to download the country names automatically from the [Unicode Common Locale Data Repository](http://cldr.unicode.org/translation/displaynames/country-names) (CLDR). We use the CLDR as the definitive source for country names, as described in the contribution guidelines.

The language file will be updated with the country names. You should then commit the changes and raise a pull request.

##### Insert country names for one or more languages:
```bash
php nova-lang country de,ru
```

##### Insert country names for all languages:
```bash
php nova-lang country --all
```

#### Stats Command

This command is to assist maintainers to update the completeness of each language and list of contributors in the README file.

The files `README.md`, `docs/introduction.md` and `contributors.json` files will be updated with the latest translated line counts and contributor usernames.

##### Update the list of languages, lines translated and contributors:
```bash
php nova-lang stats
```

Ensure you have created a GitHub personal access token and saved it in a file named `.github_token` in order to download the most recent contributions.