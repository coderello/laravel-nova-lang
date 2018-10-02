<?php

namespace Coderello\LaravelNovaLang\Providers;

use Coderello\LaravelNovaLang\Commands\NovaLangPublish;
use Illuminate\Support\ServiceProvider;

class LaravelNovaLangServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'nova');

        $this->loadJsonTranslationsFrom(__DIR__.'/../../resources/lang');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.publish.nova-lang', NovaLangPublish::class);

        $this->commands([
            'command.publish.nova-lang',
        ]);
    }
}
