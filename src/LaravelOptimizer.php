<?php

namespace Bandughana\LaravelOptimizer;

use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\Facades\Artisan;
use Appstract\Opcache\OpcacheFacade as OPcache;
use Illuminate\Console\Concerns\InteractsWithIO;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class LaravelOptimizer
{
    private $interactiveIo;
    
    /**
     * @var string[] The Laravel Page Speed package's middleware
     */
    private $pageSpeedMiddleware = [
    \RenatoMarinho\LaravelPageSpeed\Middleware\InlineCss::class,
    \RenatoMarinho\LaravelPageSpeed\Middleware\ElideAttributes::class,
    \RenatoMarinho\LaravelPageSpeed\Middleware\InsertDNSPrefetch::class,
    \RenatoMarinho\LaravelPageSpeed\Middleware\RemoveComments::class,
    //\RenatoMarinho\LaravelPageSpeed\Middleware\TrimUrls::class, 
    \RenatoMarinho\LaravelPageSpeed\Middleware\RemoveQuotes::class,
    \RenatoMarinho\LaravelPageSpeed\Middleware\CollapseWhitespace::class,
    \RenatoMarinho\LaravelPageSpeed\Middleware\DeferJavascript::class,
    ];

    /**
     * Allows this to run on terminal
     * @param \Illuminate\Console\Concerns\InteractsWithIO
     */
    public static function runOnTerminal(InteractsWithIo $io)
    {
        $me = new self;
        $me::$interactiveIo = $io;
        return $me;
    }
    
    /**
     * Registers the Laravel Page Speed middleware
     * @param \Illuminate\Foundation\Http\Kernel $kernel
     * @return \Bandughana\LaravelOptimizer\LaravelOptimizer
     */
    public function registerMiddleware(Kernel $kernel): static
    {
        foreach ($this->pageSpeedMiddleware as $middleware) {
            $kernel->pushMiddleware($middleware);
        }
        return $this;
    }

    /**
     * Optimizes images using the Laravel Image Optimizer 
     * package
     * 
     * @return \Bandughana\LaravelOptimizer\LaravelOptimizer
     */
    public function optimizeImages()
    {
        $directory = 'app/public';

        if (self::configFileExists('laravel-optimizer.php')) {
            $directory = config('laravel-optimizer.images_dir');
        }

        $images = File::allFiles(storage_path($directory));
        $optimizerChain = OptimizerChainFactory::create();
        if (! is_null($this->interactiveIo)) {
            $bar = $this->interactiveIo->output->createProgressBar(count($images));
            $bar->start();
        }

        self::forwardWithMessage(__('laravel-optimizer::messages.optimizing_imgs'));
        

        foreach ($images as $image) {
            $optimizerChain->optimize($image->getPathname());
            if (!is_null($this->interactiveIo)) {
                $bar->advance();
            }
        }

        self::forwardWithMessage('');
        self::forwardWithMessage(__('done_imgs'));

        if (!is_null($this->interactiveIo)) {
            $bar->finish();
        }

        return $this;
    }

    /**
     * Optimizes PHP files using the OpCache library
     * 
     * @return \Bandughana\LaravelOptimizer\LaravelOptimizer
     */
    public function optimizePhpCode()
    {
        self::forwardWithMessage(__('laravel-optimizer::messages.caching_php'));
        OPcache::compile(true);
        self::forwardWithMessage(__('laravel-optimizer::messages.done_php'));

        return $this;
    }

    /**
     * Runs Laravel built-in optimizations
     * 
     * @return \Bandughana\LaravelOptimizer\LaravelOptimizer
     */
    public function optimizeLaravel()
    {
        self::forwardWithMessage(__('laravel-optimizer::messages.optimizing_laravel'));
        Artisan::call('optimize --force');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        self::forwardWithMessage(__('laravel-optimizer::messages.done_laravel'));

        return $this;
    }

    /**
     * Caches Blade views
     * 
     * @return \Bandughana\LaravelOptimizer\LaravelOptimizer
     */
    public function cacheViews()
    {
        self::forwardWithMessage(__('laravel-optimizer::messages.optimizing_views'));
        Artisan::call('view:cache');
        self::forwardWithMessage(__('laravel-optimizer::messages.done_views'));

        return $this;
    }

    /**
     * Publishes configs
     * @return \Bandughana\LaravelOptimizer\LaravelOptimizer
     */
    public function publishConfigs()
    {
        Artisan::callSilent('vendor:publish --provider="Appstract\Opcache\OpcacheServiceProvider" --tag="config"');
        Artisan::callSilent('vendor:publish --provider="Spatie\LaravelImageOptimizer\ImageOptimizerServiceProvider"');
        Artisan::callSilent('vendor:publish --provider="RenatoMarinho\LaravelPageSpeed\ServiceProvider"');
        return $this;   
    }

    /**
     * Checks if the config file exists
     * @param string $fileName The config file name to check
     * @return bool
     */
    public static function configFileExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    /**
     * Print a console message and move forward
     * to a new line
     */
    private static function forwardWithMessage($message)
    {
        if (!is_null(self::$interactiveIo)) {
            self::$interactiveIo->info($message);
            self::$interactiveIo->newLine();
        }
    }

    public function reverseOptimizations()
    {
        Artisan::call('view:clear -q');
        Artisan::call('optimize:clear -q');
        Artisan::call('config:clear -q');
        Artisan::call('route:clear -q');
        Artisan::call('cache:clear -q');
        Artisan::call('opcache:clear');
        return $this;
    }
}
