<?php
namespace App\Http\Controllers\Private\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Akademik\Nilai;
use Barryvdh\DomPDF\Facade\Pdf;

class LayananController extends Controller
{
    private function page($title, $message = null, $extra = []) {
        $w=WebSetting::first();
        $u=Auth::guard('mahasiswa')->user();
        return view('private.mahasiswa.menu-page', [
            'webs'=>$w,'user'=>$u,'spref'=>$u?->prefix ?? 'mahasiswa.','menus'=>'Layanan',
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
        return Pdf::loadView('private.mahasiswa.layanan.transkrip-pdf',[
            'webs'=>WebSetting::first(),'mahasiswa'=>$user,'nilai'=>$nilai,'ipk'=>$totalSks?round($totalMutu/$totalSks,2):0,'totalSks'=>$totalSks
        ])->setPaper('a4','portrait')->download('Transkrip-'.$user->numb_nim.'.pdf');
    }

    public function suratKeterangan(){ return $this->page('Surat Keterangan'); }
    public function ajukanSuratKeterangan(Request $request){ return back()->with('success','Pengajuan surat keterangan berhasil dikirim.'); }
    public function legalisirDokumen(){ return $this->page('Legalisir Dokumen'); }
    public function ajukanLegalisir(Request $request){ return back()->with('success','Pengajuan legalisir berhasil dikirim.'); }

    public function cutiAkademik(){ return $this->page('Cuti Akademik'); }
    public function ajukanCuti(Request $request){ return back()->with('success','Pengajuan cuti akademik berhasil dikirim.'); }

    public function suratAktifKuliah(){
        $user=Auth::guard('mahasiswa')->user();
        abort_unless($user,403);
        $w=WebSetting::first();

        // Ambil jabatan dari master Jabatan Dosen jika tabel tersedia.
        // Filter hanya jabatan yang relevan untuk penandatangan surat.
        $jabatanDosen = collect();
        foreach (['jabatan_dosens', 'jabatan_dosen'] as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            $columns = Schema::getColumnListing($table);
            $nameColumn = collect(['name','nama','title','jabatan'])->first(fn($c) => in_array($c, $columns, true));
            if (!$nameColumn) {
                continue;
            }
            $jabatanDosen = DB::table($table)
                ->select(array_values(array_unique([$nameColumn, in_array('id',$columns,true) ? 'id' : $nameColumn])))
                ->where(function($q) use ($nameColumn) {
                    $q->where($nameColumn, 'like', '%Ketua STIT%')
                      ->orWhere($nameColumn, 'like', '%Pembantu Ketua%')
                      ->orWhere($nameColumn, 'like', '%Ketua%');
                })
                ->orderBy($nameColumn)
                ->get()
                ->map(fn($row) => (object)['id'=>$row->id ?? null, 'name'=>$row->{$nameColumn}])
                ->values();
            break;
        }

        // Jika master belum tersedia/masih kosong, tetap tampilkan dua jabatan resmi yang diminta.
        if ($jabatanDosen->isEmpty()) {
            $jabatanDosen = collect([
                (object)['id'=>'ketua', 'name'=>'Ketua STIT Darul Ilmi Tasikmalaya'],
                (object)['id'=>'pembantu-ketua', 'name'=>'Pembantu Ketua STIT Darul Ilmi Tasikmalaya'],
            ]);
        }

        return view('private.mahasiswa.layanan.surat-aktif-kuliah',[
            'webs'=>$w,
            'user'=>$user,
            'spref'=>$user->prefix ?? 'mahasiswa.',
            'menus'=>'Layanan',
            'pages'=>'Surat Keterangan Aktif Kuliah',
            'academy'=>$w?$w->school_apps.' by '.$w->school_name:'SIAKAD',
            'title'=>'Surat Keterangan Aktif Kuliah',
            'jabatanDosen'=>$jabatanDosen,
        ]);
    }

    public function ajukanSuratAktifKuliah(Request $request){
        $user=Auth::guard('mahasiswa')->user();
        abort_unless($user,403);
        $data=$request->validate([
            'nomor_surat'=>'nullable|string|max:100',
            'tanggal_surat'=>'required|date',
            'pejabat_nama'=>'required|string|max:150',
            'pejabat_nip'=>'required|string|max:100',
            'pejabat_jabatan'=>'required|string|max:150',
            'nik'=>'required|string|max:50',
            'ttl'=>'required|string|max:200',
            'alamat'=>'required|string|max:500',
            'jenjang'=>'required|string|max:100',
            'program_studi'=>'required|string|max:150',
            'periode_mulai'=>'required|in:Ganjil,Genap',
            'tahun_akademik'=>'required|string|max:50',
            'keperluan'=>'required|string|max:200',
        ]);
        $data['nomor_surat']=$data['nomor_surat'] ?: 'SKAK/'.now()->format('m/Y').'/'.$user->numb_nim;
        $data['nama']=$user->name;
        $data['nim']=$user->numb_nim;
        $data['mahasiswa']=$user;
        $data['webs']=WebSetting::first();
        return Pdf::loadView('private.mahasiswa.layanan.surat-aktif-kuliah-pdf',$data)
            ->setPaper('a4','portrait')
            ->download('Surat-Keterangan-Aktif-Kuliah-'.$user->numb_nim.'.pdf');
    }
}
