# Contributing

Thank you for taking the time to contribute your language to this package! It is amazing to have a community of developers and translators from around the world helping out. Contributions are **welcome** and will be fully **credited**.

Please read and understand the contribution guide before creating an issue or pull request.

## Contributing Translations

Before you commit any changes, please consider the following guidelines:
* Contributions are made by submitting a **pull request** including new language files or updates to existing files.
* If you raise an **issue** about a particular language but do not raise a PR with the suggested changes, it may not be accepted unless you clearly explain your issue.
* Please only include 1 language at a time in a PR (unless they are closely related, such as `pt`/`pt-BR` or `zh-CN`/`zh-TW`). If you can contribute in multiple languages, that's great, but please raise different requests for each language to make the review process easier.
* Ensure that the JSON file is still valid when new keys are added (i.e. no duplicate keys, no trailing or missing commas etc.)
* If you are adding a new language, ensure you translate **both** the `resources/lang/{locale}.json` and `resources/lang/{locale}/validation.php` files.
* If you have changed several existing translations, please give a brief explanation for why your versions are preferred over the existing translations.
* If you add or change a particular word in any one translation key, ensure you reuse the same word in all related keys, e.g. `Delete`, `Delete Selected`, `Are you sure you want to delete this resource?`, `The resource was successfully deleted.` etc. should all use the same verb for "delete".
* If the file has existing translations with either formal or informal ("tu/vous", "tú/usted", "du/Sie", etc.) verb forms or pronouns, ensure your additions match the same level of formality as the existing keys. If you think the whole file should be changed from formal to informal or vice versa, please raise an issue for discussion.
* Ensure that the order of the translation keys is the same as in the [source file from laravel/nova](https://github.com/laravel/nova/blob/2.0/resources/lang/en.json). You can omit keys if you have not translated them yet, but you should insert new keys into the correct order. This helps the diff on the pull request to display more usefully.
* Do not try to match the English capitalisation if your language does not usually use Title Case, e.g. `Delete Resource` should be `Eliminar recurso`, `Supprimer la ressource`, `Usuń zasób`, `Otkači resurs`, etc. as appropriate.
* There are different possible approaches to handling the limitations of Laravel's translation functionality when it comes to the gender of resources. To translate a key such as `The :resource was created!`, where the resource's name in German could be e.g. "(der) Nutzer", "(die) Seite" or "(das) Dokument", you could either leave off the article "the", i.e. `:resource wurde erstellt!` or include the word for "resource" in the translation, i.e. `Die Ressource :resource wurde erstellt!`. Decide which works best for your language and ensure that you use the same format throughout related translation keys. The least preferred option is to include all gender options, e.g. `Der/Die/Das :resource wurde erstellt!`, as this is difficult to read.
* If there is a problem where your language cannot be translated grammatically correctly because of the way Nova has formatted the English sentence, you should raise an issue in the [laravel/nova-issues](https://github.com/laravel/nova-issues/issues/) repository. We cannot fix limitations of the available keys. For example the issue of "`Create` + Resource" was fixed to `Create :resource` by raising it directly with the Nova team. You can link your Nova issue to an issue or PR in this repository if it helps.
* All country names have been imported from the [CLDR repository](http://cldr.unicode.org/) as the definitive source, so please do not modify the names of countries in this repository. If you have a genuine problem with the name of a country, please raise an issue to discuss it.
* Note that the `en.json` file in this repository has been modified from the original file from laravel/nova to provide the correct country names as above.
* There is no need to update the count of translated strings and add your username to the readme as this is done by script when the PR is merged.
* Take note of the common mistakes below which can be misleading in English and are commonly mistranslated.

### Common Mistakes

* `Reset Password Notification` means _(Reset Password)+(Notification)_ "notification of the reset of the password", not _(Reset)+(Password Notification)_ "to reset the password-notification".
* `Increase` and `Decrease` are nouns not verbs, i.e. "the increase" not "to increase".
* `Force Delete Resource` means _(Force Delete)+(Resource)_ "to permanently delete the resource", not _(Force)+(Delete Resource)_ "to force the deletion-resource"

## Contributing PHP Functionality

- **[PSR-2 Coding Standard.](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** The easiest way to apply the conventions is to install [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).
- **Add tests!** Your patch won't be accepted if it doesn't have tests.
- **Document any change in behaviour.** Make sure the `README.md` and any other relevant documentation are kept up-to-date.
- **Consider our release cycle.** We try to follow [SemVer v2.0.0](http://semver.org/). Randomly breaking public APIs is not an option.
- **Create feature branches.** Don't ask us to pull from your master branch.
- **One pull request per feature.** If you want to do more than one thing, send multiple pull requests.
- **Send coherent history.** Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.

**Happy coding!**
