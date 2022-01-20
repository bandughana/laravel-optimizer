# Laravel Optimizer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bandughana/laravel-optimizer.svg?style=for-the-badge)](https://packagist.org/packages/bandughana/laravel-optimizer)
[![GitHub license](https://img.shields.io/github/license/bandughana/laravel-optimizer?style=for-the-badge)](https://github.com/bandughana/laravel-optimizer/blob/main/LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/bandughana/laravel-optimizer.svg?style=for-the-badge)](https://packagist.org/packages/bandughana/laravel-optimizer)
[![Twitter](https://img.shields.io/twitter/url?color=blue&style=for-the-badge&url=https%3A%2F%2Fpackagist.org%2Fpackages%2Fbandughana%2Flaravel-optimizer)](https://twitter.com/intent/tweet?text=Wow:&url=https%3A%2F%2Fpackagist.org%2Fpackages%2Fbandughana%2Flaravel-optimizer)

Laravel Optimizer runs a series of optimizations on your [Laravel](https://laravel.com) project. It helps you optimize your web app before/during deployment and make it faster. The package will help you reduce your website images sizes, run the usual [Laravel deployment](laravel.com/docs/8.x/deployment) commands, minify HTML output, and run further optimizations using PHP Opcache.

## Installation

You can install the package via composer:

```bash
composer require bandughana/laravel-optimizer
```

Then, run the following Artisan command to set up the package and publish configurations:

```bash
php artisan optimizer:install
```

Behind the scenes, this package uses these awesome packages: [Laravel Opcache](https://github.com/appstract/laravel-opcache), [Laravel Image Optimizer](https://github.com/spatie/laravel-image-optimizer), and [Laravel Page Speed](https://github.com/renatomarinho/laravel-page-speed). Configurations for each of the packages will be published to your project's `config` folder after running the above command. For further package-specific configurations, consult the docs for these packages.

## Usage

In the root of your project, you can run optimizations using the Artisan command:

```bash
php artisan optimizer:run
```

If you set `reversible` to `true` in `config/laravel-optimizer.php` before running the above command, you can reverse the optimizations by running the Artisan command below:

```bash
php artisan optimizer:revert
```

You can choose to reverse all optimizations, only image optimizations, or only code optimizations by specifying a `--t|type` (`-t` or `--type`) flag with the value of one of the following: [`all`, `images`, `code`]. If you don't provide a value to this option, you'll be promted to choose one.

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email alhassankamil10@gmail.com instead of using the issue tracker.

## Credits

- [Alhassan Kamil](https://github.com/bandughana)
- [All Contributors](../../contributors)

## License

This package uses the MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## About Bandughana

Bandughana is a Ghanaian software solutions provider. Our team loves to contribute  
to open source.

Proudly made by  

[![Bandughana](bandughana.svg)](https://bandughana.com)
