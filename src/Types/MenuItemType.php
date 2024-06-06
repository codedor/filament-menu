<?php

namespace Codedor\FilamentMenu\Types;

use Illuminate\Support\HtmlString;
use Illuminate\View\View;

abstract class MenuItemType
{
    public static string $name;

    public static bool $isNormalLink = false;

    abstract public function render(array $data): ?View;

    abstract public function schema(): array;

    public function link(array $data): string | HtmlString
    {
        return '#';
    }

    public static function make(): static
    {
        return new static;
    }
}
