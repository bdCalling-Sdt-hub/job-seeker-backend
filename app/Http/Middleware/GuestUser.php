<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestUser
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (auth()->check()) {
            return $next($request);
        }

        // Allow access to specific routes for unauthenticated users
        $allowedRoutes = [
            'job-filter',
        ];

        if (in_array($request->route()->getName(), $allowedRoutes)) {
            return $next($request);
        }

        // Redirect or return unauthorized response as per your requirement
        return response()->json(['error' => 'Unauthorized.'], 401);
    }
}
