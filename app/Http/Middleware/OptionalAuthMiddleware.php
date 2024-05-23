<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->bearerToken()) {
            Auth::setUser(Auth::guard('api')->user());
        }
        return $next($request);
    }

}
