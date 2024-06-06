<?php

namespace Codedor\FilamentMenu\Types;

use App\Models\BlogPost;
use App\Models\Page;
use Codedor\TranslatableTabs\Forms\TranslatableTabs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\View\View;

class GeneratedLinks extends MenuItemType
{
    public static string $name = 'Generated Links';

    public function render(array $data): ?View
    {
        $data = $data['attributes']['data'];

        return view('filament-menu::components.types.generated-links', [
            'items' => $data['resource']::get(),
        ]);
    }

    public function schema(): array
    {
        return [
            TranslatableTabs::make()
                ->columnSpan(['lg' => 2])
                ->defaultFields([
                    Select::make('resource')
                        ->required()
                        ->options([
                            Page::class => 'Pages',
                            BlogPost::class => 'Blog Posts',
                        ]),
                ])
                ->translatableFields(fn () => [
                    Toggle::make('online')
                        ->label('Online'),
                ]),
        ];
    }
}
