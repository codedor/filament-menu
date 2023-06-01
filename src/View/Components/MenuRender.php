<?php

namespace Codedor\FilamentMenu\View\Components;

use Codedor\FilamentMenu\Models\Menu;
use Illuminate\View\Component;
use Illuminate\View\View;

class MenuRender extends Component
{
    public Menu $menu;

    public function __construct(public string $identifier)
    {
        $this->menu = Menu::where('identifier', $this->identifier)->first();
    }

    public function render(): View
    {
        dd($this->menu);

        return view('filament-menu::components.render');
    }
}
