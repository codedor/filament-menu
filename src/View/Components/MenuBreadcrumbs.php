<?php

namespace Wotz\FilamentMenu\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Wotz\FilamentMenu\Facades\MenuCollection;

class MenuBreadcrumbs extends Component
{
    public function __construct(
        public string $identifier,
        public string $view = 'filament-menu::components.render.breadcrumbs',
    ) {
        //
    }

    public function render(): View
    {
        return view($this->view, [
            'breadcrumbs' => MenuCollection::getBreadcrumbs($this->identifier),
        ]);
    }
}
