<?php
namespace App\Http\Controllers\Private\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan\WebSetting;
use App\Models\Publikasi\Pengumuman;
use App\Models\Publikasi\KalenderAkademik;
use Illuminate\Support\Facades\Auth;

class InformasiController extends Controller
{
    private function page($title, $extra = [])
    {
        $user = Auth::guard('mahasiswa')->user();
        return view('private.mahasiswa.menu-page', array_merge([
            'webs' => WebSetting::first(), 'user' => $user,
            'menus' => 'Informasi', 'pages' => $title,
            'academy' => ($w = WebSetting::first()) ? $w->school_apps . ' by ' . $w->school_name : 'SIAKAD',
            'title' => $title, 'message' => 'Informasi akan ditampilkan di halaman ini.'
        ], $extra));
    }
    public function pengumuman() {
        $items = Pengumuman::where('status','Publish')->latest()->take(10)->get();
        return $this->page('Pengumuman', ['items'=>$items,'message'=>'Daftar pengumuman kampus.']);
    }
    public function detailPengumuman($id) {
        $item = Pengumuman::where('status','Publish')->findOrFail($id);
        return $this->page('Detail Pengumuman', ['item'=>$item,'message'=>$item->title ?? $item->name ?? 'Pengumuman']);
    }
    public function kalenderAkademik() {
        $items = KalenderAkademik::where('status','Publish')->orderBy('start_date')->get();
        return $this->page('Kalender Akademik', ['items'=>$items,'message'=>'Jadwal kegiatan akademik kampus.']);
    }
    public function beasiswa() { return $this->page('Beasiswa'); }
    public function detailBeasiswa($id) { return $this->page('Detail Beasiswa', ['item'=>null]); }
    public function kontakKampus() { return $this->page('Kontak Kampus', ['message'=>WebSetting::first()?->school_address ?: 'Kontak kampus belum diisi.']); }
}
