<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // Redirect to named 'login' route if it exists, otherwise to /login
            $router = app('router');
            if (method_exists($router, 'has') && $router->has('login')) {
                return redirect()->route('login');
            }

            return redirect('/login');
        }

        return $next($request);
    }
}

