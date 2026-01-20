<?php

namespace Wotz\FilamentMenu\Http\Middleware;

use Closure;
use Wotz\FilamentMenu\Facades\MenuCollection;
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
