<?php

namespace Codedor\FilamentMenu\NavigationElements;

use Codedor\LocaleCollection\Facades\LocaleCollection;
use Codedor\LocaleCollection\Locale;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;

abstract class NavigationElement
{
    public static string $name;

    abstract public function render(array $data): ?View;

    abstract public function schema(): array;

    public function link(array $data): string|HtmlString
    {
        return '#';
    }

    public function shown(array $data): bool
    {
        return $data[app()->getLocale()]['online'] ?? false;
    }

    public function locales(array $data): array
    {
        return LocaleCollection::mapWithKeys(fn (Locale $locale) => [
            $locale->locale() => $data[$locale->locale()]['online'] ?? false,
        ])->toArray();
    }

    public static function make(): static
    {
        return new static;
    }

    public static function name(): string
    {
        return static::$name;
    }
}
