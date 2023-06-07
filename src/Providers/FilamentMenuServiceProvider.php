<?php

namespace Codedor\FilamentMenu\Providers;

use Codedor\FilamentMenu\Filament\Pages\MenuBuilder;
use Codedor\FilamentMenu\Filament\Resources\MenuResource;
use Codedor\FilamentMenu\View\Components;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;

class FilamentMenuServiceProvider extends PluginServiceProvider
{
    protected const PACKAGE_NAME = 'filament-menu';

    protected array $pages = [
        MenuBuilder::class,
    ];

    protected array $resources = [
        MenuResource::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name($this->packageName())
            ->setBasePath(__DIR__ . '/../')
            ->hasMigrations([
                '2023_05_12_083428_create_menus_table',
                '2023_05_12_083501_create_menu_items_table',
            ])
            ->runsMigrations()
            ->hasViews($this->packageName())
            ->hasTranslations();
    }

    public function bootingPackage()
    {
        Blade::component('filament-menu::render', Components\MenuRender::class);
        Blade::component('filament-menu::breadcrumbs', Components\MenuBreadcrumbs::class);
    }

    public function packageName(): string
    {
        return self::PACKAGE_NAME;
    }
}
