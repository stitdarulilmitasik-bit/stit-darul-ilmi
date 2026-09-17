<?php

namespace App\Http\Controllers\Private\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan\WebSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LayananController extends Controller
{
    private function mahasiswa()
    {
        return Auth::guard('mahasiswa')->user();
    }

    public function page(Request $request, $title = 'Layanan')
    {
        $u = $this->mahasiswa();

        return view('private.mahasiswa.menu-page', [
            'w' => WebSetting::first(),
            'user' => $u,
            'spref' => $u?->prefix ?? 'mahasiswa.',
            'menus' => 'Layanan',
            'title' => $title,
        ]);
    }

    public function cutiAkademik()
    {
        $u = $this->mahasiswa();
        return view('private.mahasiswa.layanan.cuti-akademik', [
            'w' => WebSetting::first(),
            'user' => $u,
            'spref' => $u?->prefix ?? 'mahasiswa.',
        ]);
    }

    public function suratAktifKuliah()
    {
        $u = $this->mahasiswa();
        $jabatanDosen = collect([
            (object)['id' => 'ketua', 'name' => 'Ketua STIT Darul Ilmi Tasikmalaya'],
            (object)['id' => 'wakil-ketua', 'name' => 'Wakil Ketua STIT Darul Ilmi Tasikmalaya'],
        ]);

        return view('private.mahasiswa.layanan.surat-aktif-kuliah', [
            'w' => WebSetting::first(),
            'user' => $u,
            'spref' => $u?->prefix ?? 'mahasiswa.',
            'jabatanDosen' => $jabatanDosen,
        ]);
    }

    public function ajukanSuratAktifKuliah(Request $request)
    {
        $user = $this->mahasiswa();

        $data = $request->validate([
            'nomor_surat' => 'nullable|string|max:100',
            'tanggal_surat' => 'required|date',
            'pejabat_jabatan' => 'required|in:Ketua STIT Darul Ilmi Tasikmalaya,Wakil Ketua STIT Darul Ilmi Tasikmalaya',
            'nik' => 'required|string|max:50',
            'ttl' => 'required|string|max:200',
            'alamat' => 'required|string|max:500',
            'jenjang' => 'required|in:S1,S2,S3',
            'program_studi' => 'required|string|max:150',
            'periode_mulai' => 'required|in:Ganjil,Genap',
            'tahun_akademik' => 'nullable|string|max:50',
            'keperluan' => 'required|string|max:200',
        ]);

        if (!$user) {
            abort(403, 'Sesi mahasiswa tidak ditemukan. Silakan login kembali sebagai mahasiswa.');
        }

        $penandaTangan = [
            'Ketua STIT Darul Ilmi Tasikmalaya' => [
                'nama' => 'Dr. H. Dudung Rahmat Hidayat, M.Pd.',
                'nip' => '12000',
            ],
            'Wakil Ketua STIT Darul Ilmi Tasikmalaya' => [
                'nama' => 'Aa Sudirman, M.Pd.I',
                'nip' => '12001',
            ],
        ];

        $ttd = $penandaTangan[$data['pejabat_jabatan']];
        $data['pejabat_nama'] = $ttd['nama'];
        $data['pejabat_nip'] = $ttd['nip'];

        $nim = preg_replace('/\D+/', '', (string) $user->numb_nim);
        $kodeAngkatan = substr($nim, 0, 2);

        if (strlen($kodeAngkatan) !== 2) {
            return back()
                ->withErrors(['tahun_akademik' => 'NIM mahasiswa tidak memiliki format dua digit tahun angkatan yang valid.'])
                ->withInput();
        }

        $tahunMulai = 2000 + (int) $kodeAngkatan;
        $data['tahun_akademik'] = $tahunMulai . '/' . ($tahunMulai + 1);

        $data['nomor_surat'] = $data['nomor_surat'] ?: 'SKAK/' . now()->format('m/Y') . '/' . $user->numb_nim;
        $data['nama'] = $user->name;
        $data['nim'] = $user->numb_nim;
        $data['mahasiswa'] = $user;
        $data['webs'] = WebSetting::first();

        return Pdf::loadView('private.mahasiswa.layanan.surat-aktif-kuliah-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->download('Surat-Keterangan-Aktif-Kuliah-' . $user->numb_nim . '.pdf');
    }
}
