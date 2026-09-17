<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan\WebSetting;
use App\Models\Publikasi\KalenderAkademik;
use App\Models\Akademik\JadwalKuliah;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AcademicCalendarPublicController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $year = (int) $request->query('year', $today->year);
        $month = (int) $request->query('month', $today->month);
        if ($year < 2000 || $year > 2100) $year = $today->year;
        if ($month < 1 || $month > 12) $month = $today->month;

        $monthStart = Carbon::create($year, $month, 1, 0, 0, 0, 'Asia/Jakarta');
        $monthEnd = $monthStart->copy()->endOfMonth();
        $gridStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);

        $events = KalenderAkademik::where('status', 'Publish')
            ->whereDate('start_date', '<=', $monthEnd->toDateString())
            ->where(function ($q) use ($monthStart) {
                $q->whereNull('ended_date')->whereDate('start_date', '>=', $monthStart->toDateString())
                    ->orWhere(function ($q) use ($monthStart) {
                        $q->whereNotNull('ended_date')->whereDate('ended_date', '>=', $monthStart->toDateString());
                    });
            })->orderBy('start_date')->get();

        $days = [];
        for ($date = $gridStart->copy(); $date <= $gridEnd; $date->addDay()) $days[] = $date->copy();

        // Jadwal kuliah is a weekly recurring academic schedule. Load it separately
        // so Saturday and the other teaching days are visible from the public page.
        $dayMap = ['senin'=>'Monday','monday'=>'Monday','selasa'=>'Tuesday','tuesday'=>'Tuesday','rabu'=>'Wednesday','wednesday'=>'Wednesday','kamis'=>'Thursday','thursday'=>'Thursday','jumat'=>'Friday','jum\'at'=>'Friday','friday'=>'Friday','sabtu'=>'Saturday','saturday'=>'Saturday','minggu'=>'Sunday','sunday'=>'Sunday'];
        $jadwalKuliah = JadwalKuliah::with(['mataKuliah','dosen','ruang','waktuKuliah','kelas'])
            ->orderBy('hari')->orderBy('waktu_kuliah_id')->get()
            ->map(function ($item) use ($dayMap) {
                $item->normalized_day = $dayMap[strtolower(trim((string) $item->hari))] ?? $item->hari;
                return $item;
            })->groupBy('normalized_day');

        return view('central.pages.kalender-public', [
            'webs' => WebSetting::first(),
            'today' => $today,
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
            'days' => $days,
            'events' => $events,
            'jadwalKuliah' => $jadwalKuliah,
            'user' => auth()->user(),
            'spref' => 'web-admin.',
            'menus' => 'Akademik',
            'pages' => 'Kalender Akademik',
            'academy' => 'STIT Darul Ilmi Tasikmalaya',
        ]);
    }
}
