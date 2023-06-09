<?php

namespace Codedor\FilamentMenu\Facades;

class MenuCollection extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return \Codedor\FilamentMenu\MenuCollection::class;
    }
}
