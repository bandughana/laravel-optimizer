<?php

namespace Bandughana\LaravelOptimizer\Console;

use Illuminate\Console\Command;
use Bandughana\LaravelOptimizer\LaravelOptimizer;

class Optimize extends Command
{
    protected $signature = 'optimizer:run';

    protected $description = 'Run the optimizer';

    public function handle()
    {
        $this->info(__('laravel-optimizer::messages.init_optimizations'));
        $this->newLine();

        LaravelOptimizer::runOnTerminal($this)
            ->optimizeImages()
            ->optimizeLaravel()
            ->optimizePhpCode()
            ->cacheViews();

        $this->info(__('laravel-optimizer::messages.done'));
    }
}
