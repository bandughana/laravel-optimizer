<?php

namespace Bandughana\LaravelOptimizer\Console;

use Illuminate\Console\Command;
use Bandughana\LaravelOptimizer\LaravelOptimizer;

class Reverse extends Command
{
    protected $signature = 'optimizer:revert';

    protected $description = 'Reverse optimizations';

    public function handle()
    {
        $this->info(__('laravel-optimizer::messages.init_reverse'));
        $this->newLine();

        LaravelOptimizer::runOnTerminal($this)
            ->reverseOptimizations();

        $this->info(__('laravel-optimizer::messages.done_reverse'));
    }
}
