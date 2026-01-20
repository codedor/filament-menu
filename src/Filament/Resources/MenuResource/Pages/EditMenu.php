<?php

namespace Wotz\FilamentMenu\Filament\Resources\MenuResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Wotz\FilamentMenu\Filament\Resources\MenuResource;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
