<?php

namespace Codedor\FilamentMenu\NavigationElements;

use Codedor\LinkPicker\Filament\LinkPickerInput;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;

class LinkPickerElement extends NavigationElement
{
    public static string $name = 'Normal link';

    public function render(array $data): ?View
    {
        $active = $data['active'] ?? false;
        $link = $data['attributes']['data'];

        return view('filament-menu::components.navigation-elements.link-picker-element', [
            'active' => $active,
            'label' => $link[app()->getLocale()]['label'] ?? '',
            'link' => $this->link($link),
            'children' => $data['children'] ?? [],
        ]);
    }

    public function link(array $data): string|HtmlString
    {
        return lroute($data[app()->getLocale()]['translated_link'] ?? $data['link'] ?? '');
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->columnSpan(['lg' => 2])
                ->defaultFields([
                    LinkPickerInput::make('link'),
                ])
                ->translatableFields(fn () => [
                    TextInput::make('label')
                        ->required(fn (Get $get) => $get('online')),

                    LinkPickerInput::make('translated_link')
                        ->helperText('If you want to override the link for this translation, you can do so here.'),

                    Checkbox::make('online'), // TODO: Toggle doesn't work on create ?
                ]),
        ];
    }
}
