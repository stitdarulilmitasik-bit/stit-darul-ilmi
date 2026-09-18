<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UseDosenGuard
{
    public function handle(Request $request, Closure $next)
    {
        // Dosen route selalu menggunakan guard dosen, tidak boleh jatuh
        // ke guard web/mahasiswa.
        Auth::shouldUse('dosen');

        if (!Auth::guard('dosen')->check()) {
            return redirect()->route('auth.render-signin');
        }

        return $next($request);
    }
}
