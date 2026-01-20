<?php

namespace Wotz\FilamentMenu;

use Wotz\FilamentMenu\Models\Menu;
use Wotz\FilamentMenu\Models\MenuItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Navigation\Helpers\ActiveUrlChecker;
use Spatie\Navigation\Navigation;
use Spatie\Navigation\Section;

class MenuCollection extends Collection
{
    public function fill()
    {
        if ($this->isNotEmpty()) {
            return $this;
        }

        Menu::get()->each(fn (Menu $menu) => $this->addMenu($menu));

        return $this;
    }

    public function getMenu(string $identifier): Navigation
    {
        return $this->get($identifier) ?? $this->newEmptyNavigation();
    }

    public function getTree(string $identifier): array
    {
        return $this->getMenu($identifier)->tree();
    }

    public function getBreadcrumbs(string $identifier): array
    {
        return $this->getMenu($identifier)->breadcrumbs();
    }

    public function addMenu(Menu $menu)
    {
        $navigation = $this->newEmptyNavigation();

        $navigation->add(configure: function (Section $section) use ($menu) {
            $menu->items->each(fn (MenuItem $item) => $this->addToSection($section, $item));
        });

        $this->put($menu->identifier, $navigation);

        return $this;
    }

    private function childrenCallback(MenuItem $item): callable
    {
        return fn (Section $section) => $item->children->each(function (MenuItem $item) use ($section) {
            $this->addToSection($section, $item);
        });
    }

    private function addToSection(Section $section, MenuItem $item): void
    {
        $element = new $item->type;

        if (! $element->shown($item->data)) {
            return;
        }

        $section->add(
            title: $element->title($item->data),
            url: Str::before($element->link($item->data) ?? '#', '"'),
            configure: $this->childrenCallback($item),
            attributes: [
                'type' => $item->type,
                'data' => $item->data,
            ],
        );
    }

    private function newEmptyNavigation()
    {
        $activeUrlChecker = config('filament-menu.active-url-checker', ActiveUrlChecker::class);

        return new Navigation(new $activeUrlChecker(request()->url()));
    }
}
