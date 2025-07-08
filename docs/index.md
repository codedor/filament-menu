# A menu package for filament

## Introduction

This package will add a menu page in Filament, where you can define menu's & menu items

## Navigation Elements System

The menu system uses a flexible Navigation Elements architecture that allows for different types of menu items:

```php
// config/filament-menu.php
return [
    'navigation-elements' => [
        'link-picker' => NavigationElements\LinkPickerElement::class,
    ],
];
```

Each navigation element:
- Has its own rendering logic
- Defines its own form schema
- Handles its own data structure
- Can determine visibility per locale

### Creating Custom Navigation Elements

All navigation elements extend `NavigationElement` and must implement:
- `render(array $data): ?View` - Renders the menu item
- `schema(): array` - Defines the Filament form schema
- `link(array $data): string|HtmlString` - Returns the item's URL
- `shown(array $data): bool` - Determines if the item should be displayed

To create a custom navigation element:
1. Create a class extending `NavigationElement`
2. Register it in the config
3. It will automatically appear in the menu builder

This opens possibilities for custom menu items like:
- Mega menu items
- Icon-based navigation
- Dynamic content items
- External API-driven items

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