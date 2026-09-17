<?php

namespace App\Http\Controllers\Private\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
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
    /* NOTE: Existing methods remain unchanged in the repository except for the
       schedule loader below. */

    private function getJadwalKuliah($mahasiswaId, $tahunAkademikId)
    {
        $krs = KRS::where('mahasiswa_id', $mahasiswaId)
            ->where('taka_id', $tahunAkademikId)
            ->with('details.kelas')
            ->first();

        $kelasIds = ($krs?->details ?? collect())
            ->whereIn('status', ['Aktif', 'Mengulang'])
            ->pluck('kelas_id')
            ->filter()
            ->unique()
            ->values();

        if ($kelasIds->isEmpty()) {
            return collect();
        }

        $schedules = JadwalKuliah::whereHas('kelas', function ($q) use ($kelasIds) {
                $q->whereIn('kelas.id', $kelasIds);
            })
            ->with(['mataKuliah', 'dosen', 'ruang', 'waktuKuliah', 'kelas'])
            ->orderBy('hari')
            ->orderBy('waktu_kuliah_id')
            ->get();

        // Normalize day names so both Indonesian and English database values
        // render correctly. This is especially important for Sabtu/Saturday.
        $dayMap = [
            'senin' => 'Monday', 'monday' => 'Monday',
            'selasa' => 'Tuesday', 'tuesday' => 'Tuesday',
            'rabu' => 'Wednesday', 'wednesday' => 'Wednesday',
            'kamis' => 'Thursday', 'thursday' => 'Thursday',
            'jumat' => 'Friday', 'jum\'at' => 'Friday', 'friday' => 'Friday',
            'sabtu' => 'Saturday', 'saturday' => 'Saturday',
            'minggu' => 'Sunday', 'sunday' => 'Sunday',
        ];

        return $schedules->map(function ($schedule) use ($dayMap) {
            $rawDay = trim((string) $schedule->hari);
            $key = strtolower($rawDay);
            $schedule->normalized_day = $dayMap[$key] ?? $rawDay;
            return $schedule;
        })->groupBy('normalized_day');
    }
}
