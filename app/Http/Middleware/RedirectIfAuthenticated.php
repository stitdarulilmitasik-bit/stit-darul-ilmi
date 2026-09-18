<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Jangan biarkan user yang sudah login membuka halaman signin.
     * Setiap guard diperiksa secara eksplisit agar sesi Dosen/Mahasiswa
     * tidak dianggap sebagai user web.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? ['web', 'dosen', 'mahasiswa'] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                Auth::shouldUse($guard);

                $route = $user?->prefix . 'dashboard-render';

                if ($route && \Illuminate\Support\Facades\Route::has($route)) {
                    return redirect()->route($route);
                }

                return redirect()->route('auth.render-signin');
            }
        }

        return $next($request);
    }
}
