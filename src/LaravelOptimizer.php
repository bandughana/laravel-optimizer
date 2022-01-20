<?php

namespace Bandughana\LaravelOptimizer;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Console\Concerns\HasParameters;
use Appstract\Opcache\OpcacheFacade as OPcache;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Console\OutputStyle;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Symfony\Component\Console\Helper\ProgressBar;

class LaravelOptimizer
{
    use InteractsWithIO;

    /**
     * Optimizes images using the Laravel Image Optimizer 
     * package
     */
    public function optimizeImages()
    {
        $reversible = false;

        if ($this->configFileExists('laravel-optimizer.php')) {
            $reversible = config('laravel-optimizer.reversible');
        }

        $images = $this->getImages();

        if (count($images) === 0) {
            return;
        }

        $optimizerChain = OptimizerChainFactory::create();

        $this->forwardWithMessage(__('laravel-optimizer::messages.optimizing_imgs'));
        $progressBar = $this->output->createProgressBar(count($images));
        $progressBar->setMaxSteps(count($images));

        foreach ($images as $image) {
            $imagePath = $image->getPathname();

            if ($reversible) {
                $optimizerChain->optimize($imagePath . '.lo.old', $imagePath);
            } else {
                $optimizerChain->optimize($imagePath);
            }

            $progressBar->advance();
        }
        $progressBar->finish();
    }

    /**
     * Optimizes PHP files using the OpCache library
     */
    public function optimizePhpCode()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.caching_php'));
        OPcache::compile(true);
    }

    /**
     * Ignores installation of dev packages and optimizes 
     * the autoloader during autoloader dumps
     */
    public function installPackages()
    {
        // No need to run if packages already installed
        if (File::exists(base_path('vendor'))) {
            return;
        }

        $this->forwardWithMessage(__('laravel-optimizer::messages.installing_packages'));
        exec('composer install -o --no-dev');
    }

    /**
     * Optimizes composer autoloader for faster file loads
     */
    public function optimizeComposerAutoloader()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.composer_autoload'));
        exec('composer dumpautoload -o');
    }

    /**
     * Process assets for production
     */
    public function processAssets()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.compiling_assets'));
        exec('npm run production --silent');
    }

    /**
     * Runs Laravel built-in optimizations
     */
    public function optimizeLaravel()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.optimizing_laravel'));
        Artisan::call('optimize -q');
        Artisan::call('config:cache -q');
        Artisan::call('route:cache -q');
    }

    /**
     * Caches Blade views
     */
    public function cacheViews()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.optimizing_views'));
        Artisan::call('view:cache -q');
    }

    /**
     * Publishes configs
     * @return \Bandughana\LaravelOptimizer\LaravelOptimizer
     */
    public function publishConfigs()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.publishing'));

        Artisan::call('vendor:publish', [
            '--provider' => 'Appstract\Opcache\OpcacheServiceProvider',
            '--tag' => 'config',
            '-q' => true,
        ]);

        Artisan::call('vendor:publish', [
            '--provider' => 'Spatie\LaravelImageOptimizer\ImageOptimizerServiceProvider',
            '-q' => true,
        ]);

        Artisan::call('vendor:publish', [
            '--provider' => 'RenatoMarinho\LaravelPageSpeed\ServiceProvider',
            '-q' => true,
        ]);

        Artisan::call('vendor:publish', [
            '--provider' => 'Bandughana\LaravelOptimizer\LaravelOptimizerServiceProvider',
            '--tag' => 'config'
        ]);
    }

    /**
     * Checks if the config file exists
     * @param string $fileName The config file name to check
     * @return bool
     */
    public function configFileExists($fileName)
    {
        return File::exists(config_path($fileName));
    }

    /**
     * Print a console message and move forward
     * to a new line
     */
    public function forwardWithMessage(string $message,)
    {
        $this->output = resolve('console-output');
        $this->newLine();
        $this->info($message);
    }

    /**
     * Looks for all images in the directories 
     * specified in the config file
     * 
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function getImages()
    {
        $directories = ['app/public'];
        $images = [];

        if ($this->configFileExists('laravel-optimizer.php')) {
            $directories = config('laravel-optimizer.images_dirs');
        }

        foreach ($directories as $dir) {
            $dirImages = File::allFiles(storage_path($dir));
            $images = array_merge($images, $dirImages);
        }
        return $images;
    }

    /**
     * Reverses image optimizations
     * TODO add versioned reversals
     */
    public function reverseImageOptimizations()
    {
        $images = $this->getImages();
        $start = 0;

        foreach ($images as $image) {
            if (false !== stripos($image->getFilename(), '.lo.old')) {
                $optimizedImage = substr(
                    $image->getFilename(),
                    $start,
                    stripos($image->getFilename(), '.lo.old')
                );

                $this->forwardWithMessage('Reversing ' . $optimizedImage);
                unlink($optimizedImage);
                copy($image->getPathname(), $optimizedImage);
            }
        }
    }

    /**
     * Reverses optimizations
     */
    public function reverseOptimizations()
    {
        Artisan::call('view:clear -q');
        Artisan::call('optimize:clear -q');
        Artisan::call('config:clear -q');
        Artisan::call('route:clear -q');
        Artisan::call('cache:clear -q');
        Artisan::call('opcache:clear');
    }
}
