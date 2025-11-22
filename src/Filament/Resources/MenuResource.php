<?php

namespace Codedor\FilamentMenu\Filament\Resources;

use Codedor\FilamentMenu\Filament\Pages\MenuBuilder;
use Codedor\FilamentMenu\Filament\Resources\MenuResource\Pages;
use Codedor\FilamentMenu\Models\Menu;
use Filament\Forms\Components;
use Filament\Resources\Resource;
use Filament\Tables\Columns;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bars-3';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Grid::make(1)->schema([
                Components\TextInput::make('working_title')
                    ->label(__('filament-menu::admin.working title'))
                    ->autofocus()
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),

                Components\TextInput::make('identifier')
                    ->label(__('filament-menu::admin.identifier'))
                    ->unique(ignorable: fn ($record) => $record)
                    ->hidden(fn () => ! is_superadmin())
                    ->required(),

                Components\Textarea::make('description')
                    ->label(__('filament-menu::admin.description'))
                    ->rows(3),

                Components\TextInput::make('depth')
                    ->label(__('filament-menu::admin.depth'))
                    ->hidden(fn () => ! is_superadmin())
                    ->default(1)
                    ->minValue(1)
                    ->type('number')
                    ->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Columns\TextColumn::make('working_title')
                    ->label(__('filament-menu::admin.working title')),

                Columns\TextColumn::make('identifier')
                    ->label(__('filament-menu::admin.identifier'))
                    ->hidden(fn () => ! is_superadmin()),

                Columns\TextColumn::make('description')
                    ->label(__('filament-menu::admin.description'))
                    ->hidden(fn () => is_superadmin()),

                Columns\TextColumn::make('depth')
                    ->label(__('filament-menu::admin.depth')),
            ])
            ->actions([
                \Filament\Actions\Action::make('build-menu')
                    ->label(__('filament-menu::admin.build menu'))
                    ->icon('heroicon-o-document-text')
                    ->url(fn (Menu $record): string => "menus/{$record->id}/builder"),

                \Filament\Actions\EditAction::make(),
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
