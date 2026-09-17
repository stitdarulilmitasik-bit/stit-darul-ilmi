<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Models\Akademik\KRS;
use App\Models\Akademik\JadwalKuliah;
use App\Models\Akademik\TahunAkademik;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        View::composer('private.mahasiswa.dashboard', function ($view) {
            $user = Auth::guard('mahasiswa')->user();
            if (!$user) {
                $view->with('jadwal_hari_ini', []);
                return;
            }

            $dayMap = [
                0 => 'Minggu',
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
            ];
            $dayName = $dayMap[Carbon::today()->dayOfWeek];

            $semesterAktif = TahunAkademik::where('status', 'Aktif')
                ->where('start_date', '<=', now())
                ->where('ended_date', '>=', now())
                ->first()
                ?? TahunAkademik::latest('start_date')->first();

            if (!$semesterAktif) {
                $view->with('jadwal_hari_ini', []);
                return;
            }

            $krs = KRS::where('mahasiswa_id', $user->id)
                ->where('taka_id', $semesterAktif->id)
                ->with('details')
                ->first();

            $details = $krs?->details ?? collect();
            $details = $details->whereIn('status', ['Aktif', 'Mengulang']);
            $kelasIds = $details->pluck('kelas_id')->filter()->unique()->values();
            $matkulIds = $details->pluck('matkul_id')->filter()->unique()->values();

            if ($kelasIds->isEmpty() && $matkulIds->isEmpty()) {
                $view->with('jadwal_hari_ini', []);
                return;
            }

            $query = JadwalKuliah::query()
                ->with(['mataKuliah', 'dosen', 'ruang', 'kelas', 'waktuKuliah'])
                ->whereRaw('LOWER(TRIM(hari)) = ?', [strtolower($dayName)]);

            $query->where(function ($q) use ($kelasIds, $matkulIds) {
                if ($kelasIds->isNotEmpty()) {
                    $q->whereHas('kelas', function ($kelasQuery) use ($kelasIds) {
                        $kelasQuery->whereIn('kelas.id', $kelasIds);
                    });
                }

                if ($matkulIds->isNotEmpty()) {
                    $method = $kelasIds->isNotEmpty() ? 'orWhereHas' : 'whereHas';
                    $q->{$method}('mataKuliah', function ($mkQuery) use ($matkulIds) {
                        $mkQuery->whereIn('mata_kuliahs.id', $matkulIds);
                    });
                }
            });

            $jadwal = $query->get()
                ->sortBy(function ($item) {
                    return $item->waktuKuliah?->time_start ?? '99:99:99';
                })
                ->map(function ($item) {
                    $startTime = $item->waktuKuliah
                        ? Carbon::parse($item->waktuKuliah->time_start)
                        : Carbon::today();
                    $endTime = $item->waktuKuliah
                        ? Carbon::parse($item->waktuKuliah->time_ended)
                        : Carbon::today();
                    $now = Carbon::now();

                    if ($now->between($startTime, $endTime)) {
                        $status = 'berlangsung';
                    } elseif ($now->lt($startTime)) {
                        $status = 'akan_datang';
                    } else {
                        $status = 'selesai';
                    }

                    return [
                        'mata_kuliah' => $item->mataKuliah->nama_mk ?? $item->mataKuliah->name ?? '-',
                        'bsks' => $item->mataKuliah->bsks ?? $item->mataKuliah->sks ?? 0,
                        'dosen' => $item->dosen
                            ? trim(($item->dosen->gelar_depan ? $item->dosen->gelar_depan . ' ' : '') . ($item->dosen->nama ?? $item->dosen->name ?? '') . ($item->dosen->gelar_belakang ? ', ' . $item->dosen->gelar_belakang : ''))
                            : '-',
                        'ruang' => $item->ruang->nama_ruang ?? $item->ruang->name ?? 'Tidak ada ruang',
                        'time_start' => $startTime->format('H:i'),
                        'time_ended' => $endTime->format('H:i'),
                        'metode' => $item->metode_pembelajaran ?? '-',
                        'status' => $status,
                    ];
                })
                ->values()
                ->toArray();

            $view->with('jadwal_hari_ini', $jadwal);
        });
    }
}
