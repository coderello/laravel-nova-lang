<p align="center"><img alt="Laravel Nova Lang" src="https://coderello.com/images/packages/laravel-nova-lang.png" width="450"></p>

<p align="center">Language files for <b>Laravel Nova</b> translated into 40+ languages.</p>

This is not an official Laravel package, but is built from community contributions. If you are able to help by submitting a new language, reviewing an existing language, or adding missing keys, please read our [contributing guidelines](CONTRIBUTING.md) and raise a PR.

<hr>

### Development Commands

#### Missing Command

This command is to assist contributors to find any untranslated keys for their chosen language.

A stub JSON file will be created at `build/missing/{locale}.json`. You can copy those keys into the `resources/lang/{locale}.json` language file in your own fork of the repository, translate them and create a pull request.

* Output missing translation keys for one or more languages:
```bash
php nova-lang missing de,ru
```

* Output missing translation keys for all languages:
```bash
php nova-lang missing --all
```

#### Reorder Command

This command is to assist contributors to ensure that the translation keys for their chosen language are in the same order as the `en.json` source file from Laravel Nova.

If you have imported the keys you are translating into a translation string management tool, the original order of the keys may be lost when you reexport the file. This makes the diff difficult to read because it will highlight all keys rather than just those which you have added or updated.

A new JSON file will be created at `build/reorder/{locale}.json`. You can copy the contents of this file to `resources/lang/{locale}.json` in your fork before you raise a PR.

* Output reordered translation keys for one or more languages:
```bash
php nova-lang reorder de,ru
```

* Output reordered translation keys for all languages:
```bash
php nova-lang reorder --all
```

#### Country Names Command

This command is to assist contributors to download the country names automatically from the [Unicode Common Locale Data Repository](http://cldr.unicode.org/translation/displaynames/country-names) (CLDR). We use the CLDR as the definitive source for country names, as described in the contribution guidelines.

A new JSON file will be created at `build/countries/{locale}.json`. You can merge the keys from this file into `resources/lang/{locale}.json` in your fork before you raise a PR.

* Output country names for one or more languages:
```bash
php nova-lang country de,ru
```

* Output country names for all languages:
```bash
php nova-lang country --all
```

#### Stats Command

This command is to assist maintainers to update the completeness of each language and list of contributors in this README file.

A `README.md` and `contributors.json` file will be created at `build`. You can copy those files into your own fork of the repository and create a pull request.

* Output list of languages, lines translated and contributors:
```bash
php nova-lang stats
```

Ensure you have created a GitHub personal access token and saved it in a file named `.github_token` in order to download the most recent contributions.