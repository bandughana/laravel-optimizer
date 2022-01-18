<?php

namespace Bandughana\LaravelOptimizer\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Http\Kernel;
use Bandughana\LaravelOptimizer\LaravelOptimizer;

class Install extends Command
{
    protected $signature = 'optimizer:install';

    protected $description = 'Set up the package and make it ready for running';

    public function handle(Kernel $kernel)
    {
        $this->info(__('laravel-optimizer::messages.install'));
        $this->newLine();

        LaravelOptimizer::runOnTerminal($this)
        ->registerMiddleware($kernel)
        ->publishConfigs();

        $this->info(__('laravel-optimizer::messages.done_install'));
    }
}
