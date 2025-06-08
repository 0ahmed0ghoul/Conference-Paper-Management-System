<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
    
        // Authors are always approved, or if you want to check role here:
        if ($user->role === 'author') {
            return $next($request);
        }
    
        // For chairs and reviewers, check approval status:
        if (in_array($user->role, ['chair', 'reviewer'])) {
            if ($user->status !== 'approved') {
                // Redirect to waiting page if not approved
                return redirect()->route('waiting.page');
            }
        }
    
        return $next($request);
    }
}

