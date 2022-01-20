<?php

namespace Bandughana\LaravelOptimizer\Console;

use Bandughana\LaravelOptimizer\Facades\LaravelOptimizerFacade as LaravelOptimizer;
use Illuminate\Console\Command;
use Illuminate\Foundation\Http\Kernel;

class Install extends Command
{
    protected $signature = 'optimizer:install';

    protected $description = 'Set up the package and make it ready for running';

    public function handle()
    {
        $this->info(__('laravel-optimizer::messages.install'));
        $this->registerPageSpeedMiddleware();
        LaravelOptimizer::publishConfigs();

        $this->info(__('laravel-optimizer::messages.only_done'));
        $this->warn(__('laravel-optimizer::messages.done_install'));
    }

    public function registerPageSpeedMiddleware()
    {
        $app = app();
        $kernel = new Kernel($app, $app['router']);

        $pageSpeedMiddleware = [
            '\RenatoMarinho\LaravelPageSpeed\Middleware\InlineCss::class',
            '\RenatoMarinho\LaravelPageSpeed\Middleware\ElideAttributes::class',
            '\RenatoMarinho\LaravelPageSpeed\Middleware\InsertDNSPrefetch::class',
            '\RenatoMarinho\LaravelPageSpeed\Middleware\RemoveComments::class',
            '//\RenatoMarinho\LaravelPageSpeed\Middleware\TrimUrls::class',
            '//\RenatoMarinho\LaravelPageSpeed\Middleware\RemoveQuotes::class',
            '\RenatoMarinho\LaravelPageSpeed\Middleware\CollapseWhitespace::class',
            '\RenatoMarinho\LaravelPageSpeed\Middleware\DeferJavascript::class',
        ];

        foreach ($pageSpeedMiddleware as $middleware) {
            if (!$kernel->hasMiddleware($middleware)) {
                $kernel->pushMiddleware($middleware);
                $this->info('Middleware \'' . $middleware . '\' registered.');
            }
        }
    }
}
