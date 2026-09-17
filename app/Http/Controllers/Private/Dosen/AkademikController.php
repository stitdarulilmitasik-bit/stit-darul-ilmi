<?php

namespace App\Http\Controllers\Private\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengaturan\WebSetting;
use App\Models\Akademik\TahunAkademik;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\Fakultas;
use App\Models\Akademik\Kurikulum;
use App\Models\Akademik\MataKuliah;
use App\Models\Akademik\Kelas;
use App\Models\Akademik\JadwalKuliah;

class AkademikController extends Controller
{
    public function index()
    {
        $user = Auth::guard('dosen')->user();
        abort_unless($user, 403);

        $webs = WebSetting::first();

        return view('private.dosen.akademik-master', [
            'user' => $user,
            'webs' => $webs,
            'spref' => $user->prefix ?? 'dosen.',
            'menus' => 'Akademik',
            'pages' => 'Master Akademik',
            'academy' => $webs ? $webs->school_apps . ' by ' . $webs->school_name : 'SIAKAD',
            'taka' => TahunAkademik::latest()->get(),
            'fakultas' => Fakultas::latest()->get(),
            'prodi' => ProgramStudi::with('fakultas')->latest()->get(),
            'kurikulum' => Kurikulum::latest()->get(),
            'matakuliah' => MataKuliah::with(['programStudi', 'kurikulum'])->latest()->get(),
            'kelas' => Kelas::latest()->get(),
            'jadwal' => JadwalKuliah::latest()->get(),
        ]);
    }
}
