<?php

namespace Bandughana\LaravelOptimizer;

use Illuminate\Console\OutputStyle;
use Illuminate\Support\ServiceProvider;
use Bandughana\LaravelOptimizer\Console\Install;
use Symfony\Component\Console\Input\StringInput;
use Bandughana\LaravelOptimizer\Console\Optimize;
use Bandughana\LaravelOptimizer\Console\Reverse;
use Bandughana\LaravelOptimizer\LaravelOptimizer;
use Symfony\Component\Console\Output\StreamOutput;

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
                Reverse::class,
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

        $this->app->bind('console-output', function () {
            return new OutputStyle(
                new StringInput(''),
                new StreamOutput(fopen('php://stdout', 'w'))
            );
        });
        
        $this->app->bind('laravel-optimizer', function () {
            return new LaravelOptimizer();
        });
    }
}
