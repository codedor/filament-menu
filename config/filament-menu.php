<?php

use Codedor\FilamentMenu\NavigationElements;

return [
    'navigation-elements' => [
        'link-picker' => NavigationElements\LinkPickerElement::class,
    ],

    'active-url-checker' => \Spatie\Navigation\Helpers\ActiveUrlChecker::class,
];
