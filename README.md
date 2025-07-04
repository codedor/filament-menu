# A menu package for filament

## Installation

You can install the package via composer:

```bash
composer require codedor/filament-menu
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-menu-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-menu-config"
```

This is the contents of the published config file:

```php
return [
    'active-url-checker' => \Spatie\Navigation\Helpers\ActiveUrlChecker::class,
];
```

## Documentation

For the full documentation, check [here](./docs/index.md).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

If you discover any security-related issues, please email info@codedor.be instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
