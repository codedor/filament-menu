<?php

namespace Codedor\FilamentMenu\Providers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMenuServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-menu')
            ->setBasePath(__DIR__ . '/../')
            ->hasConfigFile()
            ->hasMigration('create_package_table');
    }
}
