<?php

namespace Codedor\FilamentMenu\Filament\Resources\MenuResource\Pages;

use Codedor\FilamentMenu\Filament\Resources\MenuResource;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
