<?php

namespace Wotz\FilamentMenu\Filament\Resources\MenuResource\Pages;

use Wotz\FilamentMenu\Filament\Resources\MenuResource;
use Filament\Resources\Pages\ListRecords;

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
