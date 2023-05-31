<?php

namespace Codedor\FilamentMenu\Filament\Resources\MenuResource\Pages;

use Codedor\FilamentMenu\Filament\Resources\MenuResource;
use Codedor\FilamentMenu\Models\Menu;
use Closure;
use Codedor\LinkPicker\Forms\Components\LinkPickerInput;
use Codedor\LocaleCollection\Facades\LocaleCollection;
use Codedor\LocaleCollection\Locale;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\CreateAction;
use Illuminate\Support\Str;
use SolutionForest\FilamentTree\Actions;
use SolutionForest\FilamentTree\Concern;
use SolutionForest\FilamentTree\Resources\Pages\TreePage as BasePage;
use SolutionForest\FilamentTree\Support\Utils;

class MenuBuilder extends BasePage
{
    protected static string $resource = MenuResource::class;

    public function getModel(): string
    {
        return Menu::class;
    }

    public static function getMaxDepth(): int
    {
        return 2;
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Fields')
                ->columnSpan(['lg' => 2])
                ->tabs(fn () => [
                    Tabs\Tab::make('Default')->schema([
                        TextInput::make("{$this->prefix}.working_title")
                            ->required(),

                        LinkPickerInput::make("{$this->prefix}.link")
                            ->required(),
                    ]),
                    ...LocaleCollection::map(function (Locale $locale) {
                        return Tabs\Tab::make(Str::upper($locale->locale()))->schema([
                            TextInput::make("{$this->prefix}.label.{$locale->locale()}")
                                ->label('Label')
                                ->required(fn (Closure $get) => $get("online.{$locale->locale()}")),

                            LinkPickerInput::make("{$this->prefix}.translated_link.{$locale->locale()}")
                                ->label('Locale specific link')
                                ->helperText('If left empty, the default link will be used.'),

                            Checkbox::make("{$this->prefix}.online.{$locale->locale()}")
                                ->label('Online'),
                        ]);
                    }),
                ])
        ];
    }
}
