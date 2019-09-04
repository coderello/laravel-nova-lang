---
title: Installation
section: Getting Started
weight: 100
---

## Basic installation

You can install this package via composer using this command:

```bash
composer require coderello/laravel-nova-lang 
```

After that you need to register the `\Coderello\Laraflash\Middleware\HandleLaraflash::class` middleware after the `\Illuminate\Session\Middleware\StartSession::class` one in the `app\Http\Kernel.php`
