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
// Plugins
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function renderSignin()
    {
        $user = Auth::user() ?: Auth::guard('dosen')->user() ?: Auth::guard('mahasiswa')->user();
        $data['webs'] = WebSetting::first();
        $data['spref'] = $user ? $user->prefix : '';
        $data['menus'] = "Login";
        $data['pages'] = "Authentication";
        $data['academy'] = $data['webs']->school_apps . ' by ' . $data['webs']->school_name;

        return view('central.auth.signin-content', $data, compact('user'));
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

        // Bersihkan semua guard sebelum login agar session role sebelumnya
        // tidak ikut terbawa ke role yang baru.
        Auth::guard('web')->logout();
        Auth::guard('dosen')->logout();
        Auth::guard('mahasiswa')->logout();

        // Prioritas tetap mengikuti tabel akun, tetapi guard yang berhasil
        // ditetapkan secara eksplisit dan tidak pernah dicampur.
        $checkUser = User::where($fieldType, $login)->first();
        if ($checkUser && Auth::guard('web')->attempt([
            $fieldType => $login,
            'password' => $request->input('password')
        ])) {
            $user = Auth::guard('web')->user();
            Auth::shouldUse('web');

            Alert::toast('Kamu telah berhasil login sebagai ' . $user->name, 'success');
            return redirect()->route($user->prefix . 'dashboard-render');
        }

        $checkLecture = Dosen::where($fieldType, $login)->first();
        if ($checkLecture && Auth::guard('dosen')->attempt([
            $fieldType => $login,
            'password' => $request->input('password')
        ])) {
            $dosen = Auth::guard('dosen')->user();
            Auth::shouldUse('dosen');

            if ($dosen->type === 'Dosen Aktif') {
                Alert::toast('Kamu telah berhasil login sebagai ' . $dosen->name, 'success');
                return redirect()->route($dosen->prefix . 'dashboard-render');
            }

            Auth::guard('dosen')->logout();
            Alert::error('Akun Dosen tidak aktif', 'Akun dosen Anda tidak dapat digunakan untuk masuk.');
            return back();
        }

        $checkStudent = Mahasiswa::where($fieldType, $login)->first();
        if ($checkStudent && Auth::guard('mahasiswa')->attempt([
            $fieldType => $login,
            'password' => $request->input('password')
        ])) {
            $student = Auth::guard('mahasiswa')->user();
            Auth::shouldUse('mahasiswa');

            if (in_array($student->type, ['Calon Mahasiswa', 'Mahasiswa Aktif'], true)) {
                Alert::toast('Kamu telah berhasil login sebagai ' . $student->name, 'success');
                return redirect()->route($student->prefix . 'profile-render');
            }

            Auth::guard('mahasiswa')->logout();
        }

        RateLimiter::hit($key, $decaySeconds);
        Alert::error('Error', 'Mohon Maaf, Username / Email atau password salah');
        return back()->onlyInput('login');
    }

    public function handleLogout(Request $request) {
        if (Auth::check()) {

            Auth::logout();
            Alert::success('Berhasil!', 'Logout telah sukses!');
            return redirect()->route('auth.render-signin');
        } elseif (Auth::guard('dosen')->check()) {

            Auth::guard('dosen')->logout();
            Alert::success('Berhasil!', 'Logout telah sukses!');
            return redirect()->route('auth.render-signin');

        } elseif (Auth::guard('mahasiswa')->check()) {

            Auth::guard('mahasiswa')->logout();
            Alert::success('Berhasil!', 'Logout telah sukses!');
            return redirect()->route('auth.render-signin');

        } else {

            Alert::error('Gagal!', 'Logout gagal, Silahkan coba lagi!');
            return back();
        }
    }
}
