<?php

namespace App\Http\Controllers\Private\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Pengaturan\WebSetting;
use App\Models\Akademik\TahunAkademik;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\Fakultas;
use App\Models\Akademik\Kurikulum;
use App\Models\Akademik\MataKuliah;
use App\Models\Akademik\Kelas;
use App\Models\Akademik\JenisKelas;
use App\Models\Akademik\WaktuKuliah;
use App\Models\Akademik\JadwalKuliah;

class AkademikController extends Controller
{
    private function dosen()
    {
        $dosen = Auth::guard('dosen')->user();
        abort_unless($dosen, 403);
        return $dosen;
    }

    public function index()
    {
        $user = $this->dosen();
        $webs = WebSetting::first();
        $matakuliah = MataKuliah::with(['programStudi', 'kurikulum', 'dosen1', 'dosen2', 'dosen3'])
            ->where(function ($q) use ($user) {
                $q->where('dosen1_id', $user->id)->orWhere('dosen2_id', $user->id)->orWhere('dosen3_id', $user->id);
            })->latest()->get();

        return view('private.dosen.akademik-master', [
            'user' => $user,
            'webs' => $webs,
            'spref' => 'dosen.',
            'menus' => 'Master',
            'pages' => 'Master Akademik',
            'academy' => $webs ? $webs->school_apps . ' by ' . $webs->school_name : 'SIAKAD',
            'taka' => TahunAkademik::latest()->get(),
            'fakultas' => Fakultas::latest()->get(),
            'prodi' => ProgramStudi::with('fakultas')->latest()->get(),
            'kurikulum' => Kurikulum::latest()->get(),
            'matakuliah' => $matakuliah,
            'jenisKelas' => JenisKelas::latest()->get(),
            'kelas' => Kelas::with(['tahunAkademik', 'programStudi', 'jenisKelas'])->latest()->get(),
            'waktuKuliah' => WaktuKuliah::with('jenisKelas')->latest()->get(),
            'jadwal' => JadwalKuliah::with(['mataKuliah', 'dosen', 'ruang', 'jenisKelas', 'waktuKuliah'])
                ->where('dosen_id', $user->id)->latest()->get(),
        ]);
    }

    public function storeMataKuliah(Request $request)
    {
        $dosen = $this->dosen();
        $request->validate([
            'name' => 'required|string|max:255', 'kurikulum_id' => 'required|integer|exists:kurikulums,id',
            'prodi_id' => 'required|integer|exists:program_studis,id', 'requi_id' => 'nullable|integer|exists:mata_kuliahs,id',
            'semester' => 'required|integer|min:1|max:14', 'bsks' => 'required|string|max:10', 'desc' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 'docs_rps' => 'nullable|file|mimes:pdf|max:2048',
            'docs_kontrak_kuliah' => 'nullable|file|mimes:pdf|max:2048',
        ]);
        DB::beginTransaction();
        try {
            $photo = $request->hasFile('photo') ? $request->file('photo')->store('mata-kuliah/photo', 'public') : 'default.png';
            $docsRps = $request->hasFile('docs_rps') ? $request->file('docs_rps')->store('mata-kuliah/rps', 'public') : null;
            $docsKontrak = $request->hasFile('docs_kontrak_kuliah') ? $request->file('docs_kontrak_kuliah')->store('mata-kuliah/kontrak', 'public') : null;
            MataKuliah::create([
                'name' => $request->name, 'code' => 'MK-' . Str::random(8), 'kurikulum_id' => $request->kurikulum_id,
                'prodi_id' => $request->prodi_id, 'requi_id' => $request->requi_id, 'dosen1_id' => $dosen->id,
                'dosen2_id' => null, 'dosen3_id' => null, 'semester' => $request->semester, 'bsks' => $request->bsks,
                'desc' => $request->desc, 'photo' => $photo, 'docs_rps' => $docsRps, 'docs_kontrak_kuliah' => $docsKontrak,
                'created_by' => Auth::id(),
            ]);
            DB::commit();
            return back()->with('success', 'Mata kuliah berhasil ditambahkan dan ditetapkan kepada Anda sebagai Dosen 1.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan mata kuliah: ' . $e->getMessage())->withInput();
        }
    }

    public function updateMataKuliah(Request $request, $code)
    {
        $dosen = $this->dosen();
        $mataKuliah = MataKuliah::where('code', $code)->where(function ($q) use ($dosen) {
            $q->where('dosen1_id', $dosen->id)->orWhere('dosen2_id', $dosen->id)->orWhere('dosen3_id', $dosen->id);
        })->firstOrFail();
        $request->validate([
            'name' => 'required|string|max:255', 'kurikulum_id' => 'required|integer|exists:kurikulums,id',
            'prodi_id' => 'required|integer|exists:program_studis,id', 'requi_id' => 'nullable|integer|exists:mata_kuliahs,id',
            'semester' => 'required|integer|min:1|max:14', 'bsks' => 'required|string|max:10', 'desc' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 'docs_rps' => 'nullable|file|mimes:pdf|max:2048',
            'docs_kontrak_kuliah' => 'nullable|file|mimes:pdf|max:2048',
        ]);
        $photo = $request->hasFile('photo') ? $request->file('photo')->store('mata-kuliah/photo', 'public') : $mataKuliah->photo;
        $docsRps = $request->hasFile('docs_rps') ? $request->file('docs_rps')->store('mata-kuliah/rps', 'public') : $mataKuliah->docs_rps;
        $docsKontrak = $request->hasFile('docs_kontrak_kuliah') ? $request->file('docs_kontrak_kuliah')->store('mata-kuliah/kontrak', 'public') : $mataKuliah->docs_kontrak_kuliah;
        $mataKuliah->update([
            'name' => $request->name, 'kurikulum_id' => $request->kurikulum_id, 'prodi_id' => $request->prodi_id,
            'requi_id' => $request->requi_id, 'semester' => $request->semester, 'bsks' => $request->bsks, 'desc' => $request->desc,
            'photo' => $photo, 'docs_rps' => $docsRps, 'docs_kontrak_kuliah' => $docsKontrak, 'updated_by' => Auth::id(),
        ]);
        return back()->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function deleteMataKuliah($code)
    {
        $dosen = $this->dosen();
        $mataKuliah = MataKuliah::where('code', $code)->where(function ($q) use ($dosen) {
            $q->where('dosen1_id', $dosen->id)->orWhere('dosen2_id', $dosen->id)->orWhere('dosen3_id', $dosen->id);
        })->firstOrFail();
        if ($mataKuliah->jadwalKuliah()->count() > 0) return back()->with('error', 'Mata kuliah tidak dapat dihapus karena masih memiliki jadwal kuliah.');
        $mataKuliah->update(['deleted_by' => Auth::id()]);
        $mataKuliah->delete();
        return back()->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
