<?php
namespace App\Http\Controllers\Private\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    private function page($title, $message = null) {
        $w = WebSetting::first();
        return view('private.mahasiswa.menu-page', [
            'webs'=>$w, 'user'=>Auth::guard('mahasiswa')->user(), 'menus'=>'Keuangan',
            'pages'=>$title, 'academy'=>$w ? $w->school_apps.' by '.$w->school_name:'SIAKAD',
            'title'=>$title, 'message'=>$message ?: 'Informasi layanan keuangan mahasiswa tersedia di halaman ini.'
        ]);
    }
    public function tagihan(){ return $this->page('Tagihan Kuliah'); }
    public function detailTagihan($id){ return $this->page('Detail Tagihan'); }
    public function riwayatPembayaran(){ return $this->page('Riwayat Pembayaran'); }
    public function virtualAccount(){ return $this->page('Virtual Account'); }
    public function buktiPembayaran(){ return $this->page('Bukti Pembayaran'); }
    public function uploadBuktiPembayaran(Request $request){ return back()->with('success','Bukti pembayaran berhasil diterima untuk diproses.'); }
}
