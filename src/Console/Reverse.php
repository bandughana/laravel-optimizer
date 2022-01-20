<?php

namespace Bandughana\LaravelOptimizer\Console;

use Illuminate\Console\Command;
use Bandughana\LaravelOptimizer\Facades\LaravelOptimizerFacade as LaravelOptimizer;

class Reverse extends Command
{
    protected $signature = 'optimizer:revert {--t|type= : The optimization type to reverse}';

    protected $description = 'Reverse optimizations';

    public function handle()
    {
        $this->info(__('laravel-optimizer::messages.init_reverse'));
        $this->newLine();
        $index = 0;

        $option = $this->option('type');

        if (is_null($option)) {
            $option = $this->choice(
                'Choose optimizations to reverse:',
                ['all', 'images', 'code'],
                $index
            );
        }

        if ($option === 'images') {
            LaravelOptimizer::reverseImageOptimizations();
        } elseif ($option === 'code') {
            LaravelOptimizer::reverseOptimizations();
        } else {
            LaravelOptimizer::reverseImageOptimizations()
                ->reverseOptimizations();
        }

        $this->info(__('laravel-optimizer::messages.done_reverse'));
    }
}
