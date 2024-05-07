<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestUser
{
//    public function handle(Request $request, Closure $next)
//    {
//        // Check if the request has a token
//        if ($request->bearerToken()) {
//            return $next($request);
//        }
//
//        // Check if the user is authenticated
//        if (auth()->user()) {
//            return $next($request);
//        }
//
//        // Allow access to specific routes for unauthenticated users
//        $allowedRoutes = [
//            'job-filter', 'popular-job-post',
//        ];
//
//        if (in_array($request->route()->getName(), $allowedRoutes)) {
//            return $next($request);
//        }
//
//        // Redirect or return unauthorized response as per your requirement
//        return response()->json(['message' => 'Unauthorized.'], 401);
//    }
    public function handle(Request $request, Closure $next)
    {
        // Check if the request has a token or if the user is authenticated
        if ($request->bearerToken() || auth()->check()) {
            return $next($request);
        }

        // Allow access to specific routes for unauthenticated users
        $allowedRoutes = [
            'job-filter', 'popular-job-post',
        ];

        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }

        // Redirect or return unauthorized response as per your requirement
        return response()->json(['message' => 'Unauthorized.'], 401);
    }
}
