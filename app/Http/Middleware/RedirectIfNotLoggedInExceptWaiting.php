<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotLoggedInExceptWaiting
{
    public function handle(Request $request, Closure $next)
    {
        // Allow access to the waiting page without redirect
        if ($request->routeIs('waiting.page')) {
            if (Auth::check()) {
                return $next($request);
            }
            // If not logged in, redirect to login
            return redirect()->route('login');
        }

        // For other routes, apply the usual auth check
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
