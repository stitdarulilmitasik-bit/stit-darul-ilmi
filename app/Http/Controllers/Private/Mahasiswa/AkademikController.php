<?php

namespace App\Http\Controllers\Private\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// USE SYSTEM
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
// USE MODELS
use App\Models\Pengaturan\WebSetting;
use App\Models\Akademik\KRS;
use App\Models\Akademik\JadwalKuliah;
use App\Models\Akademik\Nilai;
use App\Models\Akademik\MataKuliah;
use App\Models\Akademik\TahunAkademik;
use App\Models\Akademik\Kelas;
use App\Models\Dosen;
use App\Models\Akademik\WaktuKuliah;
use App\Models\Infrastruktur\Ruang;
use PDF;

class AkademikController extends Controller
{
    /**
     * Display KRS (Kartu Rencana Studi) page
     * 
     * @return \Illuminate\View\View
     */

    public function krsRender()
    {
        $user = Auth::guard('mahasiswa')->user();
        abort_unless($user, 403);

        $webs = WebSetting::first();
        $currentSemester = $this->getCurrentSemester();

        $krsHeader = null;
        if ($currentSemester instanceof TahunAkademik) {
            $krsHeader = KRS::firstOrCreate(
                [
                    'mahasiswa_id' => $user->id,
                    'taka_id' => $currentSemester->id,
                    'semester' => (int) ($user->semester ?? 1),
                ],
            [
                'code' => 'KRS-' . ($user->numb_nim ?? $user->id) . '-' . now()->format('YmdHis'),
                'status' => 'Draft',
                'total_sks' => 0,
                'max_sks' => 24,
                'ipk_sebelumnya' => 0,
            ]
        );
        }

        $details = $krsHeader ? $krsHeader->details()
            ->with(['mataKuliah', 'kelas', 'dosen'])
            ->whereIn('status', ['Aktif', 'Mengulang'])
            ->get() : collect();

        $availableCourses = MataKuliah::where('prodi_id', $user->prodi_id)
            ->with(['prasyarat', 'dosen1'])
            ->orderBy('semester')
            ->orderBy('name')
            ->get();

        $data = [
            'webs' => $webs,
            'spref' => $user->prefix,
            'menus' => 'Akademik',
            'pages' => 'Kartu Rencana Studi (KRS)',
            'academy' => $webs ? $webs->school_apps . ' by ' . $webs->school_name : 'SIAKAD',
            'currentSemester' => $currentSemester,
            'krs' => $details,
            'krsHeader' => $krsHeader,
            'availableCourses' => $availableCourses,
            'user' => $user,
        ];

        return view('private.mahasiswa.akademik.krs', $data);
    }

    /**
     * Print KRS
     * 
     * @return \Illuminate\View\View
     */
    public function cetakKrs()
    {
        $user = Auth::guard('mahasiswa')->user();
        abort_unless($user, 403);
        $currentSemester = $this->getCurrentSemester();
        $krsHeader = $currentSemester
            ? KRS::where('mahasiswa_id', $user->id)->where('taka_id', $currentSemester->id)
                ->with(['dosenPA', 'mahasiswa.programStudi.fakultas', 'tahunAkademik'])->first()
            : null;
        $details = $krsHeader ? $krsHeader->details()->with(['mataKuliah', 'kelas', 'dosen'])
            ->whereIn('status', ['Aktif', 'Mengulang'])->get() : collect();
        $data = ['webs'=>WebSetting::first(), 'mahasiswa'=>$user, 'currentSemester'=>$currentSemester, 'krsHeader'=>$krsHeader, 'krs'=>$details];
        return PDF::loadView('private.mahasiswa.akademik.cetak-krs', $data)->setPaper('a4','portrait')
            ->download('KRS-'.preg_replace('/[^A-Za-z0-9_-]+/', '-', $user->name ?? $user->numb_nim ?? 'mahasiswa').'.pdf');
    }

    public function khs()
    {
        $user = Auth::guard('mahasiswa')->user();
        abort_unless($user, 403);
        $khsList = \App\Models\Akademik\KHS::with(['tahunAkademik', 'nilaiSemester.mataKuliah'])
            ->where('mahasiswa_id', $user->id)->orderBy('semester')->get();
        return view('private.mahasiswa.akademik.khs', [
            'webs'=>WebSetting::first(), 'user'=>$user, 'menus'=>'Akademik', 'pages'=>'Kartu Hasil Studi (KHS)',
            'academy'=>($w=WebSetting::first()) ? $w->school_apps.' by '.$w->school_name : 'SIAKAD', 'khsList'=>$khsList
        ]);
    }

    public function cetakKhs()
    {
        $user = Auth::guard('mahasiswa')->user();
        abort_unless($user, 403);
        $khs = \App\Models\Akademik\KHS::with(['mahasiswa.programStudi.fakultas','tahunAkademik','nilaiSemester.mataKuliah'])
            ->where('mahasiswa_id',$user->id)->where('status_generate','Published')->orderByDesc('semester')->firstOrFail();
        return PDF::loadView('master.akademik.khs-print', [
            'khs'=>$khs, 'webs'=>WebSetting::first(), 'nilai_semester'=>$khs->nilaiSemester, 'dosen_pa'=>null,
            'kaprodi'=>$khs->mahasiswa->programStudi->kaprodi ?? null
        ])->setPaper('a4','portrait')->download('KHS-'.$user->numb_nim.'-Semester-'.$khs->semester.'.pdf');
    }

    public function storeKrs(Request $request)
    {
        $user = Auth::guard('mahasiswa')->user();
        abort_unless($user, 403);

        $validated = $request->validate([
            'mata_kuliah_id' => ['required', 'integer', 'exists:mata_kuliahs,id'],
            'kelas_id' => ['nullable', 'integer', 'exists:kelas,id'],
        ]);

        $semester = $this->getCurrentSemester();
        if (!$semester) {
            return back()->with('error', 'Tahun akademik aktif belum tersedia.');
        }
        $course = MataKuliah::findOrFail($validated['mata_kuliah_id']);

        if ((int) $course->prodi_id !== (int) $user->prodi_id) {
            return back()->with('error', 'Mata kuliah bukan bagian dari program studi Anda.');
        }

        $krs = KRS::firstOrCreate(
            ['mahasiswa_id' => $user->id, 'taka_id' => $semester->id, 'semester' => (int) ($user->semester ?? 1)],
            [
                'code' => 'KRS-' . ($user->numb_nim ?? $user->id) . '-' . now()->format('YmdHis'),
                'status' => 'Draft',
                'total_sks' => 0,
                'max_sks' => 24,
                'ipk_sebelumnya' => 0,
            ]
        );

        if (!$krs->is_editable) {
            return back()->with('error', 'KRS sudah diajukan/disetujui dan tidak dapat diubah.');
        }

        $exists = $krs->details()->where('matkul_id', $course->id)->whereIn('status', ['Aktif', 'Mengulang'])->exists();
        if ($exists) {
            return back()->with('error', 'Mata kuliah tersebut sudah ada di KRS.');
        }

        if (!$krs->canAddMatakuliah((int) $course->sks)) {
            return back()->with('error', 'Batas maksimal SKS tidak mencukupi.');
        }

        $kelas = !empty($validated['kelas_id'])
            ? Kelas::find($validated['kelas_id'])
            : null;

        if ($kelas && ((int) $kelas->prodi_id !== (int) $user->prodi_id || (int) $kelas->taka_id !== (int) $semester->id)) {
            return back()->with('error', 'Kelas tidak sesuai dengan program studi atau semester aktif.');
        }

        $krs->details()->create([
            'code' => 'KRSDET-' . $user->id . '-' . $course->id . '-' . now()->format('YmdHisv'),
            'matkul_id' => $course->id,
            'kelas_id' => $kelas?->id,
            'dosen_id' => $course->dosen1_id,
            'sks' => (int) $course->sks,
            'status' => 'Aktif',
            'prasyarat_terpenuhi' => true,
        ]);

        return back()->with('success', 'Mata kuliah berhasil ditambahkan ke KRS.');
    }

    public function destroyKrs($detailId)
    {
        $user = Auth::guard('mahasiswa')->user();
        abort_unless($user, 403);

        $detail = \App\Models\Akademik\KrsDetail::where('id', $detailId)
            ->whereHas('krs', fn($q) => $q->where('mahasiswa_id', $user->id))
            ->firstOrFail();

        $detail->batalkan('Dibatalkan oleh mahasiswa');
        return back()->with('success', 'Mata kuliah berhasil dibatalkan dari KRS.');
    }

    /**
     * Display Jadwal Kuliah page for the current semester
     * 
     * @return \Illuminate\View\View
     */
    public function jadwalKuliah()
    {
        try {
            $user = Auth::guard('mahasiswa')->user();
            $currentSemester = $this->getCurrentSemester();
            
            // Get all available semesters for the student
            $availableSemesters = $this->getAvailableSemesters($user);
            
            // Get class schedule for the current semester
            $jadwal = $this->getJadwalKuliah($user->id, $currentSemester->id);
            
            $data = [
                'menus' => 'Akademik',
                'pages' => 'Jadwal Kuliah',
                'user' => $user,
                'currentSemester' => $currentSemester,
                'availableSemesters' => $availableSemesters,
                'semesters' => $availableSemesters,
                'jadwal' => $jadwal,
                'isCurrentSemester' => true
            ];
            
            return view('private.mahasiswa.akademik.jadwal-kuliah', $data);
            
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.dashboard-render')
                           ->with('error', 'Terjadi kesalahan saat memuat jadwal kuliah: ' . $e->getMessage());
        }
    }
    
    /**
     * Display Jadwal Kuliah by Semester
     * 
     * @param int $semesterId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function jadwalBySemester($semesterId)
    {
        try {
            // Validate semesterId
            if (!is_numeric($semesterId) || $semesterId <= 0) {
                return redirect()->route('mahasiswa.akademik.jadwal')
                               ->with('error', 'ID semester tidak valid');
            }
            
            $user = Auth::guard('mahasiswa')->user();
            $currentSemester = $this->getCurrentSemester();
            
            // Get the requested semester
            $tahunAkademik = TahunAkademik::findOrFail($semesterId);
            
            // Get all available semesters for the student
            $availableSemesters = $this->getAvailableSemesters($user);
            
            // Get class schedule for the requested semester
            $jadwal = $this->getJadwalKuliah($user->id, $tahunAkademik->id);
            
            $data = [
                'menus' => 'Akademik',
                'pages' => 'Jadwal Kuliah ' . $tahunAkademik->name,
                'user' => $user,
                'currentSemester' => $tahunAkademik,
                'availableSemesters' => $availableSemesters,
                'semesters' => $availableSemesters,
                'jadwal' => $jadwal,
                'isCurrentSemester' => ($tahunAkademik->id == $currentSemester->id)
            ];
            
            return view('private.mahasiswa.akademik.jadwal-kuliah', $data);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('mahasiswa.akademik.jadwal')
                           ->with('error', 'Tahun akademik tidak ditemukan');
                            
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.akademik.jadwal')
                           ->with('error', 'Terjadi kesalahan saat memuat jadwal: ' . $e->getMessage());
        }
    }
    
    /**
     * Display Presensi page
     * 
     * @return \Illuminate\View\View
     */
    public function presensi()
    {
        $user = Auth::guard('mahasiswa')->user();
        abort_unless($user, 403);

        $currentSemester = $this->getCurrentSemester();
        $summary = [];

        // Versi awal repo tidak memiliki tabel presensi akademik.
        // Tampilkan daftar mata kuliah KRS dengan status kehadiran yang aman
        // sampai modul presensi akademik diaktifkan.
        if ($currentSemester) {
            $krs = KRS::where('mahasiswa_id', $user->id)
                ->where('taka_id', $currentSemester->id)
                ->with('details.mataKuliah')
                ->first();

            foreach (($krs?->details ?? collect())->whereIn('status', ['Aktif', 'Mengulang']) as $detail) {
                $mk = $detail->mataKuliah;
                if (!$mk) continue;
                $summary[$mk->id] = [
                    'mata_kuliah' => $mk->nama ?? $mk->name,
                    'kode_mk' => $mk->kode_mk ?? $mk->code,
                    'total' => 0, 'hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0,
                    'persentase' => 0,
                ];
            }
        }

        return view('private.mahasiswa.akademik.presensi', [
            'menus' => 'Akademik', 'pages' => 'Presensi Mahasiswa', 'user' => $user,
            'currentSemester' => $currentSemester, 'summary' => $summary,
        ]);
    }

    public function detailPresensi($kodeMk)
    {
        $user = Auth::guard('mahasiswa')->user();
        abort_unless($user, 403);
        $mataKuliah = MataKuliah::where('code', $kodeMk)->orWhere('code', $kodeMk)->first();
        if (!$mataKuliah) {
            return redirect()->route('mahasiswa.akademik.presensi')->with('error', 'Mata kuliah tidak ditemukan');
        }
        return view('private.mahasiswa.akademik.detail-presensi', [
            'menus' => 'Akademik', 'pages' => 'Detail Presensi ' . ($mataKuliah->name ?? $kodeMk),
            'user' => $user, 'presensi' => collect(), 'attendances' => collect(), 'mataKuliah' => $mataKuliah,
            'currentSemester' => $this->getCurrentSemester(),
        ]);
    }

    /**
     * Display Nilai & IPK page
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function nilai()
    {
        try {
            $user = Auth::guard('mahasiswa')->user();
            
            // Get all semesters with grades
            $semesters = $this->getNilaiSemester($user->id);
            
            // Calculate IPK
            $ipkData = $this->hitungIPK($semesters);
            
            // Get available semesters for navigation
            $availableSemesters = $this->getAvailableNilaiSemesters($user->id);
            
            $data = [
                'menus' => 'Akademik',
                'pages' => 'Nilai & IPK',
                'user' => $user,
                'semesters' => $semesters,
                'ipk' => number_format($ipkData['ipk'], 2),
                'totalSks' => $ipkData['totalSks'],
                'availableSemesters' => $availableSemesters,
                'currentSemester' => $this->getCurrentSemester(),
                'isAllSemesters' => true
            ];
            
            return view('private.mahasiswa.akademik.nilai', $data);
            
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.dashboard-render')
                           ->with('error', 'Terjadi kesalahan saat memuat nilai: ' . $e->getMessage());
        }
    }
    
    /**
     * Display Nilai by Semester
     * 
     * @param int $semesterId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function nilaiBySemester($semesterId)
    {
        try {
            // Validate semesterId
            if (!is_numeric($semesterId) || $semesterId <= 0) {
                return redirect()->route('mahasiswa.akademik.nilai')
                               ->with('error', 'ID semester tidak valid');
            }
            
            $user = Auth::guard('mahasiswa')->user();
            $currentSemester = $this->getCurrentSemester();
            
            // Get the requested semester
            $tahunAkademik = TahunAkademik::select('id', 'name', 'type', 'start_date')
                                        ->findOrFail($semesterId);
            
            // Get grades for the semester
            $nilai = Nilai::where('mahasiswa_id', $user->id)
                         ->where('taka_id', $semesterId)
                         ->with([
                             'mataKuliah' => function($q) {
                                 $q->select('id', 'code', 'name', 'bsks');
                             },
                             'tahunAkademik' => function($q) {
                                 $q->select('id', 'name', 'type', 'start_date');
                             }
                         ])
                         ->orderBy('matkul_id')
                         ->get();
            
            if ($nilai->isEmpty()) {
                return redirect()->route('mahasiswa.akademik.nilai')
                               ->with('error', 'Data nilai tidak ditemukan untuk semester ini');
            }
            
            // Calculate IPS (Indeks Prestasi Semester)
            $ipsData = $this->hitungIPS($nilai);
            
            // Get available semesters for navigation
            $availableSemesters = $this->getAvailableNilaiSemesters($user->id);
            
            $data = [
                'menus' => 'Akademik',
                'pages' => 'Nilai ' . $tahunAkademik->nama,
                'user' => $user,
                'nilai' => $nilai,
                'semester' => $tahunAkademik,
                'ips' => number_format($ipsData['ips'], 2),
                'totalSks' => $ipsData['totalSks'],
                'availableSemesters' => $availableSemesters,
                'currentSemester' => $currentSemester,
                'isAllSemesters' => false
            ];
            
            return view('private.mahasiswa.akademik.nilai-semester', $data);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('mahasiswa.akademik.nilai')
                           ->with('error', 'Tahun akademik tidak ditemukan');
                            
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.akademik.nilai')
                           ->with('error', 'Terjadi kesalahan saat memuat nilai: ' . $e->getMessage());
        }
    }
    
    /**
     * Get current active semester
     * 
     * @return \App\Models\Akademik\TahunAkademik
     */
    private function getCurrentSemester()
    {
        $now = now();
        
        // Get active semester based on current date
        $semester = TahunAkademik::where('status', 'Aktif')
                                ->where('start_date', '<=', $now)
                                ->where('ended_date', '>=', $now)
                                ->first();
        
        // If no active semester found, get the latest one
        if (!$semester) {
            $semester = TahunAkademik::latest('start_date')->first();
        }
        
        // If still no semester, return a default one
        if (!$semester) {
            return null;
        }
        
        return $semester;
    }
    
    /**
     * Get available courses for KRS based on student's curriculum and prerequisites
     * 
     * @param \App\Models\Mahasiswa $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAvailableCourses($user)
    {
        $currentSemester = $this->getCurrentSemester();
        
        // Get student's program study and curriculum
        $prodi = $user->programStudi;
        if (!$prodi) {
            return collect([]);
        }
        
        // Get courses from student's curriculum that are not yet taken or failed
        $passedCourseIds = Nilai::where('mahasiswa_id', $user->id)
            ->where('status', 'Published')
            ->where('nilai_mutu', '>=', 2.00)
            ->pluck('matkul_id')->unique()->toArray();
        
        $availableCourses = MataKuliah::where('prodi_id', $prodi->id)
                                    ->whereNotIn('id', $passedCourseIds)
                                    ->with('prasyarat')
                                    ->get()
                                    ->filter(function($course) use ($passedCourseIds) {
                                        // Check if all prerequisites are met
                                        $prerequisites = $course->prasyarat->pluck('id')->toArray();
                                        return empty(array_diff($prerequisites, $passedCourseIds));
                                    });
        
        return $availableCourses;
    }
    
    /**
     * Get class schedule for a specific student and semester
     * 
     * @param int $mahasiswaId
     * @param int $tahunAkademikId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getJadwalKuliah($mahasiswaId, $tahunAkademikId)
    {
        $krs = KRS::where('mahasiswa_id', $mahasiswaId)
            ->where('taka_id', $tahunAkademikId)
            ->with('details.kelas')
            ->first();

        $kelasIds = ($krs?->details ?? collect())->pluck('kelas_id')->filter()->unique()->values();
        if ($kelasIds->isEmpty()) return collect();

        return JadwalKuliah::whereHas('kelas', fn($q) => $q->whereIn('kelas.id', $kelasIds))
            ->with(['mataKuliah', 'dosen', 'ruang', 'waktuKuliah'])
            ->orderBy('hari')->orderBy('waktu_kuliah_id')->get()->groupBy('hari');
    }

    /**
     * Get all available semesters for a student's class schedule
     * 
     * @param \App\Models\Mahasiswa $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAvailableSemesters($user)
    {
        return TahunAkademik::whereHas('krs', fn($q) => $q->where('mahasiswa_id', $user->id))
            ->orderBy('start_date', 'desc')->get();
    }

    /**
     * Get all available semesters where student has grades
     * 
     * @param int $mahasiswaId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAvailableNilaiSemesters($mahasiswaId)
    {
        return TahunAkademik::whereHas('nilai', function($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            })
            ->select('id', 'name', 'type', 'start_date')
            ->orderBy('start_date', 'desc')
            ->get();
    }
    
    /**
     * Get all semesters with grades for a student
     * 
     * @param int $mahasiswaId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getNilaiSemester($mahasiswaId)
    {
        return Nilai::where('mahasiswa_id', $mahasiswaId)
                   ->with([
                       'mataKuliah' => function($q) {
                           $q->select('id', 'code', 'name', 'bsks');
                       },
                       'tahunAkademik' => function($q) {
                           $q->select('id', 'name', 'type', 'start_date');
                       }
                   ])
                   ->orderBy('taka_id', 'desc')
                   ->orderBy('matkul_id')
                   ->get()
                   ->groupBy('taka_id');
    }
    
    /**
     * Calculate IPK (Cumulative GPA)
     * 
     * @param \Illuminate\Support\Collection $semesters
     * @return array
     */
    private function hitungIPK($semesters)
    {
        $totalSks = 0;
        $totalNilai = 0;
        
        foreach ($semesters as $semester) {
            foreach ($semester as $nilai) {
                if ($nilai->mataKuliah && $nilai->status !== 'Draft') {
                    $bobot = $this->hitungBobotNilai($nilai->nilai_angka);
                    $totalNilai += $bobot * $nilai->mataKuliah->sks;
                    $totalSks += $nilai->mataKuliah->sks;
                }
            }
        }
        
        return [
            'ipk' => $totalSks > 0 ? $totalNilai / $totalSks : 0,
            'totalSks' => $totalSks
        ];
    }
    
    /**
     * Calculate IPS (Semester GPA)
     * 
     * @param \Illuminate\Database\Eloquent\Collection $nilai
     * @return array
     */
    private function hitungIPS($nilai)
    {
        $totalSks = 0;
        $totalNilai = 0;
        
        foreach ($nilai as $n) {
            if ($n->mataKuliah && $n->status !== 'Draft') {
                $bobot = $this->hitungBobotNilai($n->nilai_angka);
                $totalNilai += $bobot * $n->mataKuliah->sks;
                $totalSks += $n->mataKuliah->sks;
            }
        }
        
        return [
            'ips' => $totalSks > 0 ? $totalNilai / $totalSks : 0,
            'totalSks' => $totalSks
        ];
    }
    
    /**
     * Calculate grade point from numeric score
     * 
     * @param float $nilaiAngka
     * @return float
     */
    private function hitungBobotNilai($nilaiAngka)
    {
        if ($nilaiAngka >= 85) return 4.00;   // A
        if ($nilaiAngka >= 80) return 3.70;   // A-
        if ($nilaiAngka >= 75) return 3.30;   // B+
        if ($nilaiAngka >= 70) return 3.00;   // B
        if ($nilaiAngka >= 65) return 2.70;   // B-
        if ($nilaiAngka >= 60) return 2.30;   // C+
        if ($nilaiAngka >= 55) return 2.00;   // C
        if ($nilaiAngka >= 50) return 1.70;   // C-
        if ($nilaiAngka >= 45) return 1.00;   // D
        return 0.00;                          // E
    }
}
