<?php

namespace Codedor\FilamentMenu\Filament\Resources\MenuResource\Pages;

use Codedor\FilamentMenu\Filament\Resources\MenuResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
