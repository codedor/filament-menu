<?php

namespace Wotz\FilamentMenu\Facades;

class MenuCollection extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return \Wotz\FilamentMenu\MenuCollection::class;
    }
}
