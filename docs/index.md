# A menu package for filament

## Introduction

This package will add a menu page in Filament, where you can define menu's & menu items

## Front-end rendering

You can display created menu's in the front-end like so, using the identifier you defined while creating the menu in Filament

```html
<x-filament-menu::render
    identifier="main"
/>
```

If you need more control over your blade files you can pass a custom blade component using the `view` attribute

```html
<x-filament-menu::render
    identifier="main"
    view="components.nav.navbar"
/>
```

## Breadcrumbs

We also provide a view component for rendering breadcrumbs, again you can define your own blade files if needed:

```php
<x-filament-menu::breadcrumbs
    identifier="main"
    view="components.breadcrumbs"
/>
```

## Custom checking of the active menu item

By default, we use the Spatie menu package to find the current active URL, if you need more control over this, you can define your own custom checker in the config:

```php
return [
    'active-url-checker' => \App\Helpers\CustomActiveUrlChecker::class,
];
```
