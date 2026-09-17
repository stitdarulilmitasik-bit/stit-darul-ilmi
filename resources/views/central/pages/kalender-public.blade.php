@extends('core-themes.core-mainpage')

@section('custom-css')
<style>
.calendar-shell{border-radius:22px;box-shadow:0 10px 35px rgba(0,0,0,.08);overflow:hidden}.calendar-toolbar{background:var(--tblr-primary);color:#fff}.calendar-grid{display:grid;grid-template-columns:repeat(7,1fr)}.calendar-weekday{padding:12px;text-align:center;font-weight:700;background:var(--tblr-bg-surface-secondary);border-bottom:1px solid var(--tblr-border-color)}.calendar-day{min-height:125px;padding:10px;border-right:1px solid var(--tblr-border-color);border-bottom:1px solid var(--tblr-border-color);background:var(--tblr-bg-surface)}.calendar-day.muted{background:var(--tblr-bg-surface-secondary);opacity:.55}.calendar-day.today{box-shadow:inset 0 0 0 3px var(--tblr-success)}.day-number{font-weight:800;margin-bottom:8px}.event{display:block;border-radius:8px;padding:6px 8px;margin-bottom:5px;font-size:.78rem;text-decoration:none;background:var(--tblr-primary-lt);color:var(--tblr-primary)}.event:hover{background:var(--tblr-primary);color:#fff}.legend-dot{width:10px;height:10px;border-radius:50%;display:inline-block;background:var(--tblr-primary)}.schedule-item{border-left:4px solid var(--tblr-primary);padding:.75rem 1rem;margin-bottom:.75rem;background:var(--tblr-bg-surface-secondary);border-radius:.5rem}.schedule-day-title{font-weight:800;color:var(--tblr-primary)}
@media(max-width:768px){.calendar-day{min-height:90px;padding:6px}.event{font-size:.7rem;padding:4px}.calendar-weekday{font-size:.75rem;padding:8px}}
</style>
@endsection

@section('content')
<div class="container-xl py-5">
    <div class="mb-4"><h1 class="fw-bold mb-2">Kalender Akademik</h1><p class="text-secondary mb-0">Tanggal mengikuti kalender berjalan Indonesia (Asia/Jakarta), agenda akademik, dan jadwal perkuliahan.</p></div>
    <div class="card calendar-shell">
        <div class="calendar-toolbar p-3 p-lg-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div><h2 class="h2 mb-1">{{ $monthStart->locale('id')->isoFormat('MMMM Y') }}</h2><div class="opacity-75">Hari ini: {{ $today->locale('id')->isoFormat('dddd, D MMMM Y') }}</div></div>
            <div class="btn-list">
                <a class="btn btn-light" href="{{ route('root.kalender-akademik-index', ['year'=>$monthStart->copy()->subMonth()->year,'month'=>$monthStart->copy()->subMonth()->month]) }}">‹ Bulan Sebelumnya</a>
                <a class="btn btn-light" href="{{ route('root.kalender-akademik-index') }}">Bulan Ini</a>
                <a class="btn btn-light" href="{{ route('root.kalender-akademik-index', ['year'=>$monthStart->copy()->addMonth()->year,'month'=>$monthStart->copy()->addMonth()->month]) }}">Bulan Berikutnya ›</a>
            </div>
        </div>
        <div class="calendar-grid">
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $weekday)<div class="calendar-weekday">{{ $weekday }}</div>@endforeach
            @foreach($days as $day)
                @php($dayEvents=$events->filter(function($event) use ($day){$start=\Carbon\Carbon::parse($event->start_date);$end=$event->ended_date?\Carbon\Carbon::parse($event->ended_date):$start;return $day->betweenIncluded($start->startOfDay(),$end->endOfDay());}))
                <div class="calendar-day {{ $day->month !== $monthStart->month ? 'muted' : '' }} {{ $day->isSameDay($today) ? 'today' : '' }}">
                    <div class="day-number">{{ $day->day }}</div>
                    @foreach($dayEvents as $event)<a class="event" href="{{ route('root.kalender-akademik-view',$event->code) }}" title="{{ $event->name }}"><strong>{{ $event->name }}</strong><br><span>{{ $event->type }}</span></a>@endforeach
                </div>
            @endforeach
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-lg-8"><div class="card"><div class="card-header"><h3 class="card-title">Agenda Bulan Ini</h3></div><div class="list-group list-group-flush">@forelse($events as $event)<a href="{{ route('root.kalender-akademik-view',$event->code) }}" class="list-group-item list-group-item-action"><div class="d-flex justify-content-between gap-3"><div><strong>{{ $event->name }}</strong><div class="text-secondary">{{ $event->type }}</div></div><div class="text-end small">{{ \Carbon\Carbon::parse($event->start_date)->locale('id')->isoFormat('D MMM Y') }}@if($event->ended_date && $event->ended_date != $event->start_date) – {{ \Carbon\Carbon::parse($event->ended_date)->locale('id')->isoFormat('D MMM Y') }}@endif</div></div></a>@empty<div class="list-group-item text-secondary">Tidak ada agenda pada bulan ini.</div>@endforelse</div></div></div>
        <div class="col-lg-4"><div class="card"><div class="card-body"><h3 class="h4">Keterangan</h3><p class="text-secondary">Tanggal hari ini diberi penanda hijau. Agenda berasal dari kalender akademik berstatus <strong>Publish</strong>. Jadwal kuliah ditampilkan sebagai agenda mingguan.</p><div><span class="legend-dot me-2"></span>Agenda akademik</div></div></div></div>
    </div>

    <div class="card mt-4">
        <div class="card-header"><div><h3 class="card-title mb-1">Jadwal Perkuliahan Mingguan</h3><div class="text-secondary small">Termasuk jadwal hari Sabtu yang sudah dibuat.</div></div></div>
        <div class="card-body">
            @php($scheduleDays=['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'])
            <div class="row">
                @foreach($scheduleDays as $engDay=>$idnDay)
                    @php($schedules=$jadwalKuliah->get($engDay,collect()))
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="schedule-item h-100">
                            <div class="schedule-day-title mb-2">{{ $idnDay }}</div>
                            @forelse($schedules as $schedule)
                                <div class="mb-2 pb-2 border-bottom">
                                    <strong>{{ $schedule->mataKuliah->nama ?? $schedule->mataKuliah->name ?? 'Mata Kuliah' }}</strong>
                                    <div class="small text-secondary">{{ $schedule->waktuKuliah ? \Carbon\Carbon::parse($schedule->waktuKuliah->time_start)->format('H:i').' - '.\Carbon\Carbon::parse($schedule->waktuKuliah->time_ended)->format('H:i') : 'Waktu belum diatur' }}</div>
                                    @if($schedule->ruang)<div class="small text-secondary">Ruang: {{ $schedule->ruang->nama_ruang ?? $schedule->ruang->name }}</div>@endif
                                    @if($schedule->dosen)<div class="small text-secondary">Dosen: {{ $schedule->dosen->nama_lengkap ?? $schedule->dosen->name }}</div>@endif
                                </div>
                            @empty
                                <div class="small text-secondary">Tidak ada jadwal.</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
