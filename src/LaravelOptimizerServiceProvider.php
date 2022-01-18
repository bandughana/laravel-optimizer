<?php

namespace Bandughana\LaravelOptimizer;

use Illuminate\Support\ServiceProvider;
use Bandughana\LaravelOptimizer\Console\Install;
use Bandughana\LaravelOptimizer\Console\Optimize;

class LaravelOptimizerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap application services.
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-optimizer');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-optimizer.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-optimizer'),
            ], 'lang');

            $this->commands([
                Optimize::class,
                Install::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-optimizer');

        $this->app->singleton('laravel-optimizer', function () {
            return new LaravelOptimizer;
        });
    }
}
