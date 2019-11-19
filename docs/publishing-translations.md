---
title: Publishing translations
section: Introduction
weight: 1000
featherIcon: terminal
---

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
