<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class CheckRecruiterMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = auth()->userOrFail();
            if ($user->userType == 'RECRUITER') {
                return $next($request);
            }
            return response()->json([
                'message' => 'Unauthorized user'
            ], 401);
        } catch (AuthenticationException $exception) {
            return response()->json([
                'message' => 'Unauthorized: ' . $exception->getMessage()
            ], 401);
        }
    }
}
