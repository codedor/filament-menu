<?php

namespace Codedor\FilamentMenu\Filament;

use Codedor\FilamentMenu\Filament\Pages\MenuBuilder;
use Codedor\FilamentMenu\Filament\Resources\MenuResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class MenuPlugin implements Plugin
{
    protected bool $hasMenuResource = true;

    protected bool $hasMenuBuilderPage = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-menu';
    }

    public function register(Panel $panel): void
    {
        if ($this->hasMenuResource()) {
            $panel->resources([
                MenuResource::class,
            ]);
        }

        if ($this->hasMenuBuilderPage()) {
            $panel->pages([
                MenuBuilder::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
    }

    public function menuResource(bool $condition = true): static
    {
        // This is the setter method, where the user's preference is
        // stored in a property on the plugin object.
        $this->hasMenuResource = $condition;

        // The plugin object is returned from the setter method to
        // allow fluent chaining of configuration options.
        return $this;
    }

    public function hasMenuResource(): bool
    {
        // This is the getter method, where the user's preference
        // is retrieved from the plugin property.
        return $this->hasMenuResource;
    }

    public function menuBuilderPage(bool $condition = true): static
    {
        $this->hasMenuBuilderPage = $condition;

        return $this;
    }

    public function hasMenuBuilderPage(): bool
    {
        return $this->hasMenuBuilderPage;
    }
}
