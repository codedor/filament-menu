<?php

namespace Wotz\FilamentMenu\NavigationElements;

use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Wotz\LocaleCollection\Facades\LocaleCollection;
use Wotz\LocaleCollection\Locale;

/** @phpstan-consistent-constructor */
abstract class NavigationElement
{
    public static string $name;

    abstract public function render(array $data): ?View;

    abstract public function schema(): array;

    public function link(array $data): string|HtmlString
    {
        return '#';
    }

    public function hasTargetBlank(array $data): bool
    {
        return false;
    }

    public function title(array $data): string
    {
        return (string) ($this->translated($data, 'label') ?? '');
    }

    public function shown(array $data): bool
    {
        return (bool) ($this->translated($data, 'online') ?? false);
    }

    public function locales(array $data): array
    {
        return LocaleCollection::mapWithKeys(fn (Locale $locale) => [
            $locale->locale() => (bool) ($this->translated($data, 'online', $locale->locale()) ?? false),
        ])->toArray();
    }

    /**
     * Read a translated value whichever way it was stored.
     *
     * TranslatableTabs v3 dehydrates to `$data[$field][$locale]`, v2 to
     * `$data[$locale][$field]`, and composer.json allows both — so a menu
     * item written under one of them must stay readable under the other.
     */
    protected function translated(array $data, string $field, ?string $locale = null): mixed
    {
        $locale ??= app()->getLocale();

        return $data[$field][$locale] ?? $data[$locale][$field] ?? null;
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
