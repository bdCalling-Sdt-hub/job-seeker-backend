<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserMiddleware
{
    public function guard()
    {
        return Auth::guard('api');
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->guard()->user()->userType == 'USER') {
            return $next($request);
        }
        return response()->json('Unauthorized user',401);
    }
}
