<?php

namespace Wotz\FilamentMenu\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Wotz\FilamentMenu\Facades\MenuCollection;

class MenuRender extends Component
{
    public function __construct(
        public string $identifier,
        public string $view = 'filament-menu::components.render.root',
    ) {
        MenuCollection::fill();
    }

    public function render(): View
    {
        return view($this->view, [
            'navigation' => MenuCollection::getTree($this->identifier)[0]['children'] ?? [],
        ]);
    }
}
