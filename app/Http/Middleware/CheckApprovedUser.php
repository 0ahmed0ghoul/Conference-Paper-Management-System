<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckApprovedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Skip approval check for authors
            if ($user->role !== 'author' && $user->status !== 'approved') {
                Auth::logout();
                return redirect()
                    ->route('login')
                    ->withErrors(['Your account is not approved yet.']);
            }
        }

        return $next($request);
    }
}