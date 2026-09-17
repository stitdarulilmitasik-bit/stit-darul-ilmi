<?php

namespace App\Http\Controllers\Private\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JadwalKuliah;
use App\Models\Akademik\KRS;
use App\Models\Akademik\KrsDetail;
use App\Models\Akademik\Nilai;
use App\Models\Akademik\MataKuliah;
use App\Models\Akademik\Kelas;
use App\Models\Akademik\JenisKelas;
use App\Models\Akademik\WaktuKuliah;
use App\Models\Infrastruktur\Ruang;
use App\Models\Pengaturan\WebSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AkademikOperasionalController extends Controller
{
    private function dosen()
    {
        $dosen = Auth::guard('dosen')->user();
        abort_unless($dosen, 403);
        return $dosen;
    }

    private function base($page)
    {
        $webs = WebSetting::first();
        return [
            'user' => $this->dosen(),
            'webs' => $webs,
            'spref' => 'dosen.',
            'menus' => 'Akademik',
            'pages' => $page,
            'academy' => $webs ? $webs->school_apps . ' by ' . $webs->school_name : 'SIAKAD',
        ];
    }

    public function jadwal()
    {
        $data = $this->base('Jadwal Kuliah');
        $dosenId = $data['user']->id;
        $data['jadwal'] = JadwalKuliah::with(['mataKuliah','kelas','ruang','jenisKelas','waktuKuliah'])
            ->where('dosen_id', $dosenId)->latest()->get();
        $data['mata_kuliah'] = MataKuliah::where(function($q) use ($dosenId) {
            $q->where('dosen1_id', $dosenId)->orWhere('dosen2_id', $dosenId)->orWhere('dosen3_id', $dosenId);
        })->get();
        $data['kelas'] = Kelas::latest()->get();
        $data['jenis_kelas'] = JenisKelas::with('waktuKuliah')->get();
        $data['waktu_kuliah'] = WaktuKuliah::all();
        $data['ruang'] = Ruang::all();
        return view('private.dosen.akademik-jadwal', $data);
    }

    public function simpanJadwal(Request $request)
    {
        $dosen = $this->dosen();
        $request->validate([
            'ruang_id'=>'required|integer|exists:ruangs,id', 'matkul_id'=>'required|integer|exists:mata_kuliahs,id',
            'jenis_kelas_id'=>'required|integer|exists:jenis_kelas,id', 'waktu_kuliah_id'=>'required|integer|exists:waktu_kuliahs,id',
            'bsks'=>'required|integer|min:1|max:6', 'pertemuan'=>'required|integer|min:1', 'hari'=>'required|string',
            'metode'=>'required|string|in:Tatap Muka,Teleconference', 'tanggal'=>'required|date', 'link'=>'nullable|url',
            'kelas_ids'=>'required|array', 'kelas_ids.*'=>'integer|exists:kelas,id'
        ]);
        $allowed = MataKuliah::where('id',$request->matkul_id)->where(function($q) use ($dosen) {
            $q->where('dosen1_id',$dosen->id)->orWhere('dosen2_id',$dosen->id)->orWhere('dosen3_id',$dosen->id);
        })->exists();
        abort_unless($allowed, 403, 'Mata kuliah bukan mata kuliah yang diampu.');
        $jadwal = JadwalKuliah::create([
            'code'=>'JDW-'.Str::random(8), 'dosen_id'=>$dosen->id, 'ruang_id'=>$request->ruang_id, 'matkul_id'=>$request->matkul_id,
            'jenis_kelas_id'=>$request->jenis_kelas_id, 'waktu_kuliah_id'=>$request->waktu_kuliah_id, 'bsks'=>$request->bsks,
            'pertemuan'=>$request->pertemuan, 'hari'=>$request->hari, 'metode'=>$request->metode, 'tanggal'=>$request->tanggal,
            'link'=>$request->link, 'created_by'=>Auth::id()
        ]);
        $jadwal->kelas()->attach($request->kelas_ids);
        return back()->with('success','Jadwal kuliah berhasil ditambahkan.');
    }

    public function updateJadwal(Request $request, $code)
    {
        $dosen = $this->dosen();
        $jadwal = JadwalKuliah::where('code',$code)->where('dosen_id',$dosen->id)->firstOrFail();
        $request->validate([
            'ruang_id'=>'required|integer|exists:ruangs,id', 'matkul_id'=>'required|integer|exists:mata_kuliahs,id',
            'jenis_kelas_id'=>'required|integer|exists:jenis_kelas,id', 'waktu_kuliah_id'=>'required|integer|exists:waktu_kuliahs,id',
            'bsks'=>'required|integer|min:1|max:6', 'pertemuan'=>'required|integer|min:1', 'hari'=>'required|string',
            'metode'=>'required|string|in:Tatap Muka,Teleconference', 'tanggal'=>'required|date', 'link'=>'nullable|url',
            'kelas_ids'=>'required|array', 'kelas_ids.*'=>'integer|exists:kelas,id'
        ]);
        $jadwal->update($request->only(['ruang_id','matkul_id','jenis_kelas_id','waktu_kuliah_id','bsks','pertemuan','hari','metode','tanggal','link']) + ['updated_by'=>Auth::id()]);
        $jadwal->kelas()->sync($request->kelas_ids);
        return back()->with('success','Jadwal kuliah berhasil diperbarui.');
    }

    public function nilai()
    {
        $data = $this->base('Nilai Mahasiswa');
        $id = $data['user']->id;
        $data['nilai'] = Nilai::with(['mahasiswa','mataKuliah','tahunAkademik','krsDetail'])
            ->whereHas('krsDetail', fn($q) => $q->where('dosen_id',$id))->latest()->paginate(30);
        return view('private.dosen.akademik-nilai', $data);
    }

    public function updateNilai(Request $request, $code)
    {
        $dosen = $this->dosen();
        $nilai = Nilai::where('code',$code)->whereHas('krsDetail', fn($q) => $q->where('dosen_id',$dosen->id))->firstOrFail();
        abort_unless($nilai->status === 'Draft', 403, 'Nilai sudah dipublish atau dikunci.');
        $request->validate([
            'tugas_1'=>'nullable|numeric|min:0|max:100','tugas_2'=>'nullable|numeric|min:0|max:100','tugas_3'=>'nullable|numeric|min:0|max:100',
            'quiz_1'=>'nullable|numeric|min:0|max:100','quiz_2'=>'nullable|numeric|min:0|max:100','uts'=>'nullable|numeric|min:0|max:100',
            'uas'=>'nullable|numeric|min:0|max:100','praktikum'=>'nullable|numeric|min:0|max:100','kehadiran'=>'nullable|numeric|min:0|max:100',
            'notes'=>'nullable|string'
        ]);
        $nilai->update($request->only(['tugas_1','tugas_2','tugas_3','quiz_1','quiz_2','uts','uas','praktikum','kehadiran','notes']) + ['updated_by'=>Auth::id()]);
        $nilai->refresh();
        $nilai->hitungNilaiAkhir();
        return back()->with('success','Nilai berhasil diperbarui.');
    }

    public function krs()
    {
        $data = $this->base('Persetujuan KRS');
        $id = $data['user']->id;
        $data['krs'] = KRS::with(['mahasiswa','tahunAkademik','dosenPA','details.mataKuliah','details.kelas','details.dosen'])
            ->whereHas('details', fn($q) => $q->where('dosen_id',$id))->whereIn('status',['Diajukan','Ditolak'])->latest()->paginate(20);
        return view('private.dosen.akademik-krs', $data);
    }

    public function approveKrs($code)
    {
        $dosen = $this->dosen();
        $krs = KRS::where('code',$code)->whereHas('details', fn($q) => $q->where('dosen_id',$dosen->id))->firstOrFail();
        abort_unless($krs->status === 'Diajukan', 403, 'KRS tidak sedang menunggu persetujuan.');
        $krs->approve($dosen->id, request('notes'));
        return back()->with('success','KRS berhasil disetujui.');
    }

    public function rejectKrs(Request $request, $code)
    {
        $dosen = $this->dosen();
        $krs = KRS::where('code',$code)->whereHas('details', fn($q) => $q->where('dosen_id',$dosen->id))->firstOrFail();
        abort_unless($krs->status === 'Diajukan', 403, 'KRS tidak sedang menunggu persetujuan.');
        $request->validate(['notes'=>'required|string']);
        $krs->reject($request->notes);
        return back()->with('success','KRS berhasil ditolak.');
    }
}
