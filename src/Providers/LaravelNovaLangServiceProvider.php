<?php

namespace Coderello\LaravelNovaLang\Providers;

use Coderello\LaravelNovaLang\Commands\NovaLangPublish;
use Coderello\LaravelNovaLang\Commands\NovaLangMissing;
use Coderello\LaravelNovaLang\Commands\NovaLangStats;
use Coderello\LaravelNovaLang\Commands\NovaLangReorder;
use Coderello\LaravelNovaLang\Commands\NovaLangCountry;
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

        $this->commands([
            'command.publish.nova-lang',
        ]);

        if (config('app.debug')) {
            $this->app->singleton('command.missing.nova-lang', NovaLangMissing::class);
            $this->app->singleton('command.stats.nova-lang', NovaLangStats::class);
            $this->app->singleton('command.reorder.nova-lang', NovaLangReorder::class);
            $this->app->singleton('command.country.nova-lang', NovaLangCountry::class);

            $this->commands([
                'command.missing.nova-lang',
                'command.stats.nova-lang',
                'command.reorder.nova-lang',
                'command.country.nova-lang',
            ]);
        }
    }
}
