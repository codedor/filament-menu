<?php

namespace Wotz\FilamentMenu\Filament\Resources\MenuResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Wotz\FilamentMenu\Filament\Resources\MenuResource;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
