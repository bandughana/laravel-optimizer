<?php

namespace Bandughana\LaravelOptimizer\Console;

use Bandughana\LaravelOptimizer\Facades\LaravelOptimizerFacade as LaravelOptimizer;
use Illuminate\Console\Command;

class Optimize extends Command
{
    protected $signature = 'optimizer:run';

    protected $description = 'Run the optimizer';

    public function handle()
    {
        $this->info(__('laravel-optimizer::messages.init_optimizations'));

        LaravelOptimizer::installPackages()
            ->optimizeImages()
            ->processAssets()
            ->optimizeComposerAutoloader()
            ->optimizeLaravel()
            // ->optimizePhpCode()
            ->cacheViews();

        $this->newLine();
        $this->info(__('laravel-optimizer::messages.done'));
    }
}
