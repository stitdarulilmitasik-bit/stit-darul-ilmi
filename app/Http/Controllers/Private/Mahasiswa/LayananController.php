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

    private function layoutData($title, array $extra = [])
    {
        $u = $this->mahasiswa();
        $webs = WebSetting::first();

        return array_merge([
            'w' => $webs,
            'webs' => $webs,
            'user' => $u,
            'spref' => $u?->prefix ?? 'mahasiswa.',
            'menus' => 'Layanan',
            'pages' => $title,
            'academy' => 'STIT Darul Ilmi Tasikmalaya',
            'title' => $title,
            'message' => 'Silakan pilih layanan yang tersedia.',
        ], $extra);
    }

    public function page(Request $request, $title = 'Layanan')
    {
        return view('private.mahasiswa.menu-page', $this->layoutData($title));
    }

    public function transkripNilai()
    {
        return view('private.mahasiswa.menu-page', $this->layoutData('Transkrip Nilai', [
            'message' => 'Halaman transkrip nilai mahasiswa.',
        ]));
    }

    public function cetakTranskrip()
    {
        return view('private.mahasiswa.menu-page', $this->layoutData('Transkrip Nilai', [
            'message' => 'Transkrip nilai mahasiswa.',
            'nilai' => collect(),
            'totalSks' => 0,
            'ipk' => 0,
        ]));
    }

    public function legalisirDokumen()
    {
        return view('private.mahasiswa.menu-page', $this->layoutData('Legalisir Dokumen', [
            'message' => 'Halaman pengajuan legalisir dokumen mahasiswa.',
        ]));
    }

    public function ajukanLegalisir(Request $request)
    {
        return back()->with('success', 'Pengajuan legalisir dokumen berhasil dikirim.');
    }

    public function ajukanCuti(Request $request)
    {
        return back()->with('success', 'Pengajuan cuti akademik berhasil dikirim.');
    }

    public function cutiAkademik()
    {
        return view('private.mahasiswa.layanan.cuti-akademik', $this->layoutData('Cuti Akademik'));
    }

    public function suratAktifKuliah()
    {
        $jabatanDosen = collect([
            (object)['id' => 'ketua', 'name' => 'Ketua STIT Darul Ilmi Tasikmalaya'],
            (object)['id' => 'wakil-ketua', 'name' => 'Wakil Ketua STIT Darul Ilmi Tasikmalaya'],
        ]);

        return view('private.mahasiswa.layanan.surat-aktif-kuliah', $this->layoutData('Surat Keterangan Aktif Kuliah', [
            'jabatanDosen' => $jabatanDosen,
        ]));
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
