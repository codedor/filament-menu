<?php

use Illuminate\View\View;
use Wotz\FilamentMenu\NavigationElements\NavigationElement;
use Wotz\LocaleCollection\Facades\LocaleCollection;
use Wotz\LocaleCollection\Locale;

/**
 * composer.json allows wotz/filament-translatable-tabs ^2.1|^3.0, and the two
 * dehydrate a menu item's translations into different shapes:
 *
 *   v2  $data[$locale][$field]   ["nl" => ["label" => "Home", "online" => true]]
 *   v3  $data[$field][$locale]   ["label" => ["nl" => "Home"], "online" => [...]]
 *
 * The elements used to read only the v2 shape, so an item saved through the
 * builder on v3 came back with shown() === false and vanished from the menu.
 */
beforeEach(function () {
    LocaleCollection::add(new Locale('nl'));
    LocaleCollection::add(new Locale('fr'));

    $this->element = new class extends NavigationElement
    {
        public static string $name = 'Test';

        public function render(array $data): ?View
        {
            return null;
        }

        public function schema(): array
        {
            return [];
        }
    };
});

$localeKeyed = [
    'nl' => ['label' => 'Home', 'online' => true],
    'fr' => ['label' => 'Accueil', 'online' => false],
];

$fieldKeyed = [
    'label' => ['nl' => 'Home', 'fr' => 'Accueil'],
    'online' => ['nl' => true, 'fr' => false],
];

it('reads the title from either shape', function (array $data) {
    app()->setLocale('nl');
    expect($this->element->title($data))->toBe('Home');

    app()->setLocale('fr');
    expect($this->element->title($data))->toBe('Accueil');
})->with([
    'locale-keyed (translatable-tabs v2)' => [$localeKeyed],
    'field-keyed (translatable-tabs v3)' => [$fieldKeyed],
]);

it('reads the online flag from either shape', function (array $data) {
    app()->setLocale('nl');
    expect($this->element->shown($data))->toBeTrue();

    app()->setLocale('fr');
    expect($this->element->shown($data))->toBeFalse();
})->with([
    'locale-keyed (translatable-tabs v2)' => [$localeKeyed],
    'field-keyed (translatable-tabs v3)' => [$fieldKeyed],
]);

it('reports per-locale availability from either shape', function (array $data) {
    expect($this->element->locales($data))->toBe(['nl' => true, 'fr' => false]);
})->with([
    'locale-keyed (translatable-tabs v2)' => [$localeKeyed],
    'field-keyed (translatable-tabs v3)' => [$fieldKeyed],
]);

it('falls back to an empty title and a hidden item when a locale is missing', function (array $data) {
    app()->setLocale('de');

    expect($this->element->title($data))->toBe('')
        ->and($this->element->shown($data))->toBeFalse();
})->with([
    'locale-keyed (translatable-tabs v2)' => [$localeKeyed],
    'field-keyed (translatable-tabs v3)' => [$fieldKeyed],
]);
