<?php

namespace Codedor\FilamentMenu\Types;

use Codedor\LinkPicker\Filament\LinkPickerInput;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;

class LinkPickerLink extends MenuItemType
{
    public static string $name = 'Link Picker Link';

    public static bool $isNormalLink = true;

    public function render(array $data): ?View
    {
        $active = $data['active'] ?? false;
        $link = $data['attributes']['data'];

        return view('filament-menu::components.types.link-picker-link', [
            'active' => $active,
            'label' => $link[app()->getLocale()]['label'] ?? '',
            'link' => $this->link($link),
            'children' => $data['children'] ?? [],
        ]);
    }

    public function link(array $data): string | HtmlString
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
                        ->label('Label')
                        ->required(fn (Get $get) => $get('online')),

                    LinkPickerInput::make('translated_link')
                        ->label('Link')
                        ->helperText('If you want to override the link for this translation, you can do so here.'),

                    Toggle::make('online')
                        ->label('Online'),
                ]),
        ];
    }
}
