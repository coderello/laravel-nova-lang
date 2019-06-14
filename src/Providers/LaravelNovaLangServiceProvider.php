<?php

namespace Coderello\LaravelNovaLang\Providers;

use Coderello\LaravelNovaLang\Commands\NovaLangPublish;
use Coderello\LaravelNovaLang\Commands\NovaLangMissing;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.publish.nova-lang', NovaLangPublish::class);
        $this->app->singleton('command.missing.nova-lang', NovaLangMissing::class);
        
        $this->commands([
            'command.publish.nova-lang',
            'command.missing.nova-lang',
        ]);
    }
}
