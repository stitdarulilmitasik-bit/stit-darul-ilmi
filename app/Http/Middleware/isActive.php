<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isActive
{
    /**
     * Memastikan akun pada guard yang sedang dipakai berstatus aktif.
     * Middleware ini sengaja tidak mencari user dari guard lain.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.render-signin');
        }

        if ((int) $user->status === 1) {
            return $next($request);
        }

        return redirect()->route('error.access');
    }
}
