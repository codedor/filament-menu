<?php

namespace Codedor\FilamentMenu\Providers;

use Codedor\FilamentMenu\View\Components;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMenuServiceProvider extends PackageServiceProvider
{
    protected const PACKAGE_NAME = 'filament-menu';

    public function configurePackage(Package $package): void
    {
        $package
            ->name($this->packageName())
            ->setBasePath(__DIR__ . '/../')
            ->hasMigrations([
                '2023_05_12_083428_create_menus_table',
                '2023_05_12_083501_create_menu_items_table',
            ])
            ->hasConfigFile()
            ->runsMigrations()
            ->hasViews($this->packageName())
            ->hasTranslations();
    }

    public function bootingPackage(): void
    {
        Blade::component('filament-menu::render', Components\MenuRender::class);
        Blade::component('filament-menu::breadcrumbs', Components\MenuBreadcrumbs::class);
    }

    public function packageName(): string
    {
        return self::PACKAGE_NAME;
    }
}
