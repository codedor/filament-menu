<?php

namespace Codedor\FilamentMenu\Http\Middleware;

use Closure;
use Codedor\FilamentMenu\Facades\MenuCollection;
use Codedor\FilamentMenu\Models\Menu;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (MenuCollection::isNotEmpty()) {
            return $next($request);
        }

        Menu::get()->each(function (Menu $menu) {
            MenuCollection::addMenu($menu);
        });

        return $next($request);
    }
}
