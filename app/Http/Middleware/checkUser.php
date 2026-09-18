<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class checkUser
{
    /**
     * Memastikan request hanya dilayani oleh guard yang sesuai dengan role.
     * Setiap role memiliki session/guard sendiri sehingga akun tidak tercampur.
     */
    public function handle(Request $request, Closure $next, $userType): Response
    {
        $guard = match ($userType) {
            'Web Administrator',
            'Departement Akademik',
            'Departement Keuangan',
            'Departement Kemahasiswaan',
            'Departement Infrastruktur & IT',
            'Departement Perpustakaan',
            'Departement Umum',
            'Departement Admisi' => 'web',
            'Dosen Aktif' => 'dosen',
            'Mahasiswa Aktif', 'Calon Mahasiswa' => 'mahasiswa',
            default => null,
        };

        if (!$guard) {
            Alert::error('Error', 'Role pengguna tidak dikenali.');
            return redirect()->route('auth.render-signin');
        }

        // Jangan pernah mengambil user dari guard lain.
        Auth::shouldUse($guard);

        if (!Auth::guard($guard)->check()) {
            // Jika ada sesi role lain, jangan biarkan sesi tersebut mengakses
            // route role ini. Arahkan kembali ke halaman login.
            return redirect()->route('auth.render-signin');
        }

        $user = Auth::guard($guard)->user();

        // Hard isolation: satu request/role hanya boleh menyimpan satu guard aktif.
        // Ini mencegah sesi Dosen/Mahasiswa lama ikut terbawa ketika membuka Admin,
        // dan sebaliknya.
        foreach (['web', 'dosen', 'mahasiswa'] as $otherGuard) {
            if ($otherGuard !== $guard) {
                Auth::guard($otherGuard)->logout();
            }
        }
        Auth::shouldUse($guard);

        if (!$user || $user->type !== $userType) {
            return redirect()->route('error.access');
        }

        return $next($request);
    }
}
