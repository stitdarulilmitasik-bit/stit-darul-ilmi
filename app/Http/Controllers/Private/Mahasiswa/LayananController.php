<?php
namespace App\Http\Controllers\Private\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan\WebSetting;
use App\Models\Akademik\Nilai;
use App\Models\Layanan\CutiAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class LayananController extends Controller
{
    private function page($title, $message = null, $extra = []) {
        $w = WebSetting::first();
        $user = Auth::guard('mahasiswa')->user();
        return view('private.mahasiswa.menu-page', [
            'webs' => $w,
            'user' => $user,
            'spref' => $user?->prefix ?? 'mahasiswa.',
            'menus' => 'Layanan',
            'pages' => $title,
            'academy' => $w ? $w->school_apps.' by '.$w->school_name : 'SIAKAD',
            'title' => $title,
            'message' => $message ?: 'Layanan mahasiswa tersedia pada halaman ini.'
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

    public function cutiAkademik(){
        $user = Auth::guard('mahasiswa')->user();
        abort_unless($user, 403);

        $pengajuan = CutiAkademik::where('mahasiswa_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('private.mahasiswa.layanan.cuti-akademik', [
            'webs' => WebSetting::first(),
            'user' => $user,
            'spref' => $user->prefix,
            'menus' => 'Layanan',
            'pages' => 'Cuti Akademik',
            'academy' => 'SIAKAD STIT by STIT Darul Ilmi',
            'pengajuan' => $pengajuan,
        ]);
    }

    public function ajukanCuti(Request $request){
        $user = Auth::guard('mahasiswa')->user();
        abort_unless($user, 403);

        $data = $request->validate([
            'semester' => ['required','string','max:30'],
            'tanggal_mulai' => ['nullable','date'],
            'tanggal_selesai' => ['nullable','date','after_or_equal:tanggal_mulai'],
            'alasan' => ['required','string','max:2000'],
            'alamat_selama_cuti' => ['nullable','string','max:500'],
            'no_telepon' => ['nullable','string','max:30'],
            'file_pendukung' => ['nullable','file','mimes:pdf,jpg,jpeg,png','max:2048'],
        ]);

        if ($request->hasFile('file_pendukung')) {
            $data['file_pendukung'] = $request->file('file_pendukung')->store('cuti-akademik', 'public');
        }

        $data['mahasiswa_id'] = $user->id;
        $data['tanggal_pengajuan'] = now()->toDateString();
        $data['status'] = 'Diajukan';

        CutiAkademik::create($data);

        return redirect()->route('mahasiswa.layanan.cuti')->with('success', 'Pengajuan cuti akademik berhasil dikirim dan menunggu verifikasi akademik.');
    }
}
