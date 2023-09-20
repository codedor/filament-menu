<?php

namespace Codedor\FilamentMenu\Http\Middleware;

use Closure;
use Codedor\FilamentMenu\Facades\MenuCollection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        MenuCollection::fill();

        return $next($request);
    }
}
