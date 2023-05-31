<?php

namespace Codedor\FilamentMenu\Filament\Resources;

use Codedor\FilamentMenu\Filament\Pages\MenuBuilder;
use Codedor\FilamentMenu\Filament\Resources\MenuResource\Pages;
use Codedor\FilamentMenu\Models\Menu;
use Filament\Forms\Components;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Components\Grid::make(1)->schema([
                Components\TextInput::make('working_title')
                    ->autofocus()
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),

                Components\TextInput::make('identifier')
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),

                Components\Textarea::make('description')
                    ->rows(3),

                Components\TextInput::make('depth')
                    ->default(0)
                    ->type('number')
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Columns\TextColumn::make('working_title'),
                Columns\TextColumn::make('identifier'),
                Columns\TextColumn::make('depth'),
            ])
            ->actions([
                Tables\Actions\Action::make('build-menu')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (Menu $record): string => "menus/{$record->id}/builder"),

                // TODO: check if admin is allowed to create/edit/delete menus
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'build-menu' => MenuBuilder::route('/{record}/builder'),
        ];
    }
}
