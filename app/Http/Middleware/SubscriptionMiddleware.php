<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionMiddleware
{
    public function guard()
    {
        return Auth::guard('api');
    }

    public function handle(Request $request, Closure $next): Response
    {

        if ($this->guard()->user()->userType == 'USER' && $this->guard()->user()->user_status == 1 ){
            return $next($request);
        }
        return response()->json([
            'message' => 'Subscription is not complete yet',
        ],402);
    }
}
