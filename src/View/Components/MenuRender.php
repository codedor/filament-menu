<?php

namespace Codedor\FilamentMenu\View\Components;

use Codedor\FilamentMenu\Facades\MenuCollection;
use Illuminate\View\Component;
use Illuminate\View\View;

class MenuRender extends Component
{
    public function __construct(public string $identifier)
    {

    }

    public function render(): View
    {
        return view('filament-menu::components.render.root', [
            'navigation' => MenuCollection::getTree($this->identifier),
        ]);
    }
}
