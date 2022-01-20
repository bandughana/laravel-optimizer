<?php

namespace Bandughana\LaravelOptimizer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bandughana\LaravelOptimizer\LaravelOptimizer
 */
class LaravelOptimizerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-optimizer';
    }
}
