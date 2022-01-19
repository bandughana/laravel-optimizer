<?php
namespace Bandughana\LaravelOptimizer\Tests;

use Bandughana\LaravelOptimizer\LaravelOptimizerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase {

    protected function getPackageProviders($app)
    {
        return [
            LaravelOptimizerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}