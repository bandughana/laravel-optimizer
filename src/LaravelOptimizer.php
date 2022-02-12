<?php

namespace Bandughana\LaravelOptimizer;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Appstract\Opcache\OpcacheFacade as OPcache;
use Illuminate\Console\Concerns\InteractsWithIO;
use Spatie\ImageOptimizer\OptimizerChainFactory;

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
            return $this;
        }

        $optimizerChain = OptimizerChainFactory::create();

        $this->forwardWithMessage(__('laravel-optimizer::messages.optimizing_imgs'));
        $progressBar = $this->output->createProgressBar(count($images));

        foreach ($images as $image) {
            $imagePath = $image->getPathname();
            $oldImagePath = $imagePath . '.lo.old';
            copy($imagePath, $oldImagePath);

            if ($reversible) {
                $optimizerChain->optimize($oldImagePath, $imagePath);
            } else {
                $optimizerChain->optimize($imagePath);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        return $this;
    }

    /**
     * Optimizes PHP files using the OpCache library
     */
    public function optimizePhpCode()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.caching_php'));
        OPcache::compile(true);
        return $this;
    }

    /**
     * Ignores installation of dev packages and optimizes
     * the autoloader during autoloader dumps
     */
    public function installPackages()
    {
        // No need to run if packages already installed
        if (File::exists(base_path('vendor'))) {
            return $this;
        }

        $this->forwardWithMessage(__('laravel-optimizer::messages.installing_packages'));
        exec('composer install -o --no-dev');
        return $this;
    }

    /**
     * Optimizes composer autoloader for faster file loads
     */
    public function optimizeComposerAutoloader()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.composer_autoload'));
        exec('composer dumpautoload -o -q');
        return $this;
    }

    /**
     * Process assets for production
     */
    public function processAssets()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.compiling_assets'));
        $bar = $this->output->createProgressBar();
        $bar->start();

        $lockFile = base_path('l-o-output.txt');
        if (file_exists($lockFile)) {
            return $this;
        }

        $handler = popen('npm run production', 'r');

        while ($b = fgets($handler, 2048)) {
            $bar->advance();
        }
        pclose($handler);
        $bar->finish();

        return $this;
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
        return $this;
    }

    /**
     * Caches Blade views
     */
    public function cacheViews()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.optimizing_views'));
        Artisan::call('view:cache -q');
        return $this;
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

        return $this;
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

        $acceptedFileExts = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'old'];
        foreach ($directories as $dir) {

            $dirImages = array_filter(
                File::allFiles(storage_path($dir)),
                function (
                    \Symfony\Component\Finder\SplFileInfo $file
                ) use ($acceptedFileExts) {
                    return $file->isFile() && in_array(
                        $file->getExtension(),
                        $acceptedFileExts
                    );
                }
            );

            $images = array_merge($images, $dirImages);
        }
        foreach ($images as $image) {
            $this->forwardWithMessage($image);
        }
        return $images;
    }

    /**
     * Reverses image optimizations
     * #TODO add versioned reversals
     */
    public function reverseImageOptimizations()
    {
        $images = $this->getImages();
        $start = 0;

        if (count($images) <= 0) {
            return $this;
        }

        foreach ($images as $image) {
            if (stripos($image->getFilename(), '.lo.old')) {
                $optimizedImage = substr(
                    $image->getRealPath(),
                    $start,
                    stripos($image->getRealPath(), '.lo.old')
                );

                $this->forwardWithMessage(__(
                    'laravel-optimizer::messages.reversing_image',
                    ['image' => $optimizedImage]
                ));

                unlink($optimizedImage);
                copy($image->getRealPath(), $optimizedImage);
                unlink($image->getRealPath());
            }
        }
        return $this;
    }

    /**
     * Reverses optimizations
     */
    public function reverseOptimizations()
    {
        $this->forwardWithMessage(__('laravel-optimizer::messages.reversing_code'));
        Artisan::call('view:clear -q');
        Artisan::call('optimize:clear -q');
        Artisan::call('config:clear -q');
        Artisan::call('route:clear -q');
        Artisan::call('cache:clear -q');
        // Artisan::call('opcache:clear');
        return $this;
    }
}
