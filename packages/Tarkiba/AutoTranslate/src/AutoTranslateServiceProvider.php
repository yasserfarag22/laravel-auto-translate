<?php

namespace Tarkiba\AutoTranslate;

use Illuminate\Support\ServiceProvider;

class AutoTranslateServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/auto-translate.php', 'auto-translate'
        );

        $this->app->singleton('auto-translate', function ($app) {
            return new TranslationManager();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/auto-translate.php' => config_path('auto-translate.php'),
        ], 'config');

        require_once __DIR__.'/helpers.php';
    }
}
