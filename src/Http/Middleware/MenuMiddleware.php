<?php

namespace Wotz\FilamentMenu\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Wotz\FilamentMenu\Facades\MenuCollection;

class MenuMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        MenuCollection::fill();

        return $next($request);
    }
}
