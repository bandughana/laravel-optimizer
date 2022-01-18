# Laravel Optimizer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bandughana/laravel-optimizer.svg?style=flat-square)](https://packagist.org/packages/bandughana/laravel-optimizer)
[![Total Downloads](https://img.shields.io/packagist/dt/bandughana/laravel-optimizer.svg?style=flat-square)](https://packagist.org/packages/bandughana/laravel-optimizer)
![GitHub Actions](https://github.com/bandughana/laravel-optimizer/actions/workflows/main.yml/badge.svg)

Laravel Optimizer runs a series of optimizations on your [Laravel](https://laravel.com) project. It helps you optimize your web app before/during deployment and make it faster. The package will help you reduce your website images sizes, run the usual [Laravel](laravel.com/docs/8.x/deployment) deployment commands, minify HTML output, and run further optimizations using PHP Opcache.

## Installation

You can install the package via composer:

```bash
composer require bandughana/laravel-optimizer
```

Then, run the following `artisan` command to set up the package and publish configurations:

```bash
php artisan optimize:install
```

Behind the scenes, this package uses these awesome packages: [Laravel Opcache](https://github.com/appstract/laravel-opcache), [Laravel Image Optimizer](https://github.com/spatie/laravel-image-optimizer), and [Laravel Page Speed](https://github.com/renatomarinho/laravel-page-speed). Configurations for each of the packages will be published to your project's `config` folder after running the above command. For further configurations, consult the docs for these packages.

## Usage

In the root of your project, you can run optimizations using the `artisan` command:

```bash
php artisan optimize:run
```

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
