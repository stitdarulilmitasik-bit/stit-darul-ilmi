<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Models
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pengaturan\WebSetting;
// Auth
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// Plugins
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function renderSignin()
    {
        // Halaman login harus netral. Jangan pernah mengambil user dari
        // guard lain karena itu dapat membuat identitas role terbawa ke view.
        $data['webs'] = WebSetting::first();
        $data['spref'] = '';
        $data['menus'] = "Login";
        $data['pages'] = "Authentication";
        $data['academy'] = $data['webs']->school_apps . ' by ' . $data['webs']->school_name;

        return view('central.auth.signin-content', $data, ['user' => null]);
    }

    public function handleSignin(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $login = $request->input('login');

        $settings = WebSetting::first();
        $maxAttempts = $settings->max_login_attempts ?? 5;
        $decaySeconds = $settings->login_decay_seconds ?? 60;
        $key = 'login:' . Str::lower($login) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            Alert::error('Terlalu banyak percobaan', "Coba lagi dalam {$seconds} detik.");
            return back()->withErrors(['login' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."])->onlyInput('login');
        }

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $password = $request->input('password');

        // Satu request hanya boleh memiliki satu role aktif.
        $this->logoutAllGuards();

        // Cari akun pada setiap provider. Jika identifier yang sama ternyata
        // dimiliki lebih dari satu provider, jangan memilih role secara
        // arbitrer karena dapat menyebabkan akun masuk ke role yang salah.
        $candidates = [];

        $webUser = User::where($fieldType, $login)->first();
        if ($webUser && Hash::check($password, $webUser->password)) {
            $candidates[] = ['guard' => 'web', 'user' => $webUser];
        }

        $dosen = Dosen::where($fieldType, $login)->first();
        if ($dosen && Hash::check($password, $dosen->password)) {
            $candidates[] = ['guard' => 'dosen', 'user' => $dosen];
        }

        $student = Mahasiswa::where($fieldType, $login)->first();
        if ($student && Hash::check($password, $student->password)) {
            $candidates[] = ['guard' => 'mahasiswa', 'user' => $student];
        }

        // Tolak kredensial yang cocok dengan lebih dari satu provider.
        if (count($candidates) > 1) {
            $this->logoutAllGuards();
            RateLimiter::hit($key, $decaySeconds);
            Alert::error(
                'Akun ganda terdeteksi',
                'Username / Email ini terdaftar pada lebih dari satu jenis akun. Silakan gunakan identifier yang unik atau hubungi administrator.'
            );
            return back()->onlyInput('login');
        }

        if (count($candidates) === 1) {
            $candidate = $candidates[0];
            $guard = $candidate['guard'];
            $user = $candidate['user'];

            // Dosen hanya boleh masuk bila statusnya aktif.
            if ($guard === 'dosen' && $user->type !== 'Dosen Aktif') {
                RateLimiter::hit($key, $decaySeconds);
                Alert::error('Akun Dosen tidak aktif', 'Akun dosen Anda tidak dapat digunakan untuk masuk.');
                return back()->onlyInput('login');
            }

            // Mahasiswa hanya boleh masuk bila statusnya valid.
            if ($guard === 'mahasiswa' && !in_array($user->type, ['Calon Mahasiswa', 'Mahasiswa Aktif'], true)) {
                RateLimiter::hit($key, $decaySeconds);
                Alert::error('Akun Mahasiswa tidak aktif', 'Akun mahasiswa Anda tidak dapat digunakan untuk masuk.');
                return back()->onlyInput('login');
            }

            Auth::guard($guard)->login($user);
            Auth::shouldUse($guard);
            $request->session()->regenerate();
            RateLimiter::clear($key);

            Alert::toast('Kamu telah berhasil login sebagai ' . $user->name, 'success');

            // Prefix tetap berasal dari model akun yang baru saja login.
            return redirect()->route($user->prefix . 'dashboard-render');
        }

        RateLimiter::hit($key, $decaySeconds);
        Alert::error('Error', 'Mohon Maaf, Username / Email atau password salah');
        return back()->onlyInput('login');
    }

    public function handleLogout(Request $request)
    {
        $hadAuthenticatedGuard =
            Auth::guard('web')->check() ||
            Auth::guard('dosen')->check() ||
            Auth::guard('mahasiswa')->check();

        // Bersihkan semua guard agar tidak ada role lama yang tertinggal
        // pada session/cookie saat pengguna berpindah akun.
        $this->logoutAllGuards();

        // Putuskan session lama sepenuhnya setelah logout.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($hadAuthenticatedGuard) {
            Alert::success('Berhasil!', 'Logout telah sukses!');
            return redirect()->route('auth.render-signin');
        }

        Alert::error('Gagal!', 'Tidak ada sesi pengguna yang aktif.');
        return redirect()->route('auth.render-signin');
    }

    private function logoutAllGuards(): void
    {
        Auth::guard('web')->logout();
        Auth::guard('dosen')->logout();
        Auth::guard('mahasiswa')->logout();
        Auth::shouldUse('web');
    }
}
