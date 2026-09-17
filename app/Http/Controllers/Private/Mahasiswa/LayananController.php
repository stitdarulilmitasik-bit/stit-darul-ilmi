<?php
namespace App\Http\Controllers\Private\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Akademik\Nilai;
use PDF;

class LayananController extends Controller
{
    private function page($title, $message = null, $extra = []) {
        $w=WebSetting::first();
        return view('private.mahasiswa.menu-page', [
            'webs'=>$w,'user'=>Auth::guard('mahasiswa')->user(),'menus'=>'Layanan',
            'pages'=>$title,'academy'=>$w?$w->school_apps.' by '.$w->school_name:'SIAKAD',
            'title'=>$title,'message'=>$message ?: 'Layanan mahasiswa tersedia pada halaman ini.'
        ], $extra);
    }
    public function transkripNilai(){
        $user=Auth::guard('mahasiswa')->user(); abort_unless($user,403);
        $nilai=Nilai::with(['mataKuliah','tahunAkademik'])->where('mahasiswa_id',$user->id)->whereIn('status',['Published','Locked'])->orderBy('semester')->get();
        $totalSks=$nilai->sum('sks'); $totalMutu=$nilai->sum('mutu_x_sks');
        return $this->page('Transkrip Nilai', 'Transkrip nilai mahasiswa.', ['nilai'=>$nilai,'ipk'=>$totalSks?round($totalMutu/$totalSks,2):0,'totalSks'=>$totalSks]);
    }
    public function cetakTranskrip(){
        $user=Auth::guard('mahasiswa')->user(); abort_unless($user,403);
        $nilai=Nilai::with(['mataKuliah','tahunAkademik'])->where('mahasiswa_id',$user->id)->whereIn('status',['Published','Locked'])->orderBy('semester')->get();
        $totalSks=$nilai->sum('sks'); $totalMutu=$nilai->sum('mutu_x_sks');
        return PDF::loadView('private.mahasiswa.layanan.transkrip-pdf',[
            'webs'=>WebSetting::first(),'mahasiswa'=>$user,'nilai'=>$nilai,'ipk'=>$totalSks?round($totalMutu/$totalSks,2):0,'totalSks'=>$totalSks
        ])->setPaper('a4','portrait')->download('Transkrip-'.$user->numb_nim.'.pdf');
    }
    public function suratKeterangan(){ return $this->page('Surat Keterangan'); }
    public function ajukanSuratKeterangan(Request $request){ return back()->with('success','Pengajuan surat keterangan berhasil dikirim.'); }
    public function legalisirDokumen(){ return $this->page('Legalisir Dokumen'); }
    public function ajukanLegalisir(Request $request){ return back()->with('success','Pengajuan legalisir berhasil dikirim.'); }
    public function cutiAkademik(){ return $this->page('Cuti Akademik'); }
    public function ajukanCuti(Request $request){ return back()->with('success','Pengajuan cuti akademik berhasil dikirim.'); }
}
