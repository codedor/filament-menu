<?php

namespace Codedor\FilamentMenu;

use Codedor\FilamentMenu\Models\Menu;
use Codedor\FilamentMenu\Models\MenuItem;
use Illuminate\Support\Collection;
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

        Menu::get()->each(function (Menu $menu) {
            $this->addMenu($menu);
        });

        return $this;
    }

    public function addMenu(Menu $menu)
    {
        $navigation = $this->newEmptyNavigation();

        $menu->items->each(function (MenuItem $item) use ($navigation) {
            $navigation->add($item->label ?? '', $item->route ?? '', $this->childrenCallback($item));
        });

        $this->put($menu->identifier, $navigation);

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

    private function childrenCallback(MenuItem $item): callable
    {
        return fn (Section $section) => $item->children->each(function (MenuItem $item) use ($section) {
            $section->add($item->label, $item->route, $this->childrenCallback($item));
        });
    }

    private function newEmptyNavigation()
    {
        $activeUrlChecker = config('filament-menu.active-url-checker', ActiveUrlChecker::class);

        return new Navigation(new $activeUrlChecker(request()->url()));
    }
}
