<?php

namespace Bandughana\LaravelOptimizer\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallTest extends \Orchestra\Testbench\TestCase {
    /**
     * @test
     */
    function package_config_is_published_after_running_install_command()
    {
        if (File::exists(config_path('laravel-optimizer.php'))) {
            unlink(config_path('laravel-optimizer.php'));
        }

        $this->assertFalse(File::exists(config_path('laravel-optimizer.php')));
        Artisan::call('optimizer:install');
        $this->assertTrue(File::exists(config_path('laravel-optimizer.php')));
        
    }

    /**
     * @test
     */
    function it_registered_the_laravel_page_speed_middleware()
    {
        $kernel = $this->app->make('Illuminate\Foundation\Http\Kernel');
        $this->assertFalse($kernel->hasMiddleware(\RenatoMarinho\LaravelPageSpeed\Middleware\InlineCss::class));
        Artisan::call('optimizer:install');
        $this->assertTrue($kernel->hasMiddleware(\RenatoMarinho\LaravelPageSpeed\Middleware\InlineCss::class));
        
    }
    /**
     * @test
     */
    function it_published_all_packages_configs()
    {
        if (File::exists(config_path('opcache.php'))) {
            unlink(config_path('opcache.php'));
        }

        if (File::exists(config_path('laravel-page-speed.php'))) {
            unlink(config_path('laravel-page-speed.php'));
        }

        if (File::exists(config_path('image-optimizer.php'))) {
            unlink(config_path('image-optimizer.php'));
        }

        $this->assertFalse(File::exists(config_path('opcache.php')));
        $this->assertFalse(File::exists(config_path('laravel-page-speed.php')));
        $this->assertFalse(File::exists(config_path('image-optimizer.php')));
        
        Artisan::call('optimizer:install');
        $this->assertTrue(File::exists(config_path('opcache.php')));
        $this->assertTrue(File::exists(config_path('laravel-page-speed.php')));
        $this->assertTrue(File::exists(config_path('image-optimizer.php')));
    }
} 