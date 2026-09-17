<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UseDosenGuard
{
    public function handle(Request $request, Closure $next)
    {
        Auth::shouldUse('dosen');

        return $next($request);
    }
}
