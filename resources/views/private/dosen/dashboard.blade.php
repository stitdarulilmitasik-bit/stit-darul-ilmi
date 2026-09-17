@extends('core-themes.core-backpage')

@section('custom-css')
<style>
    .stat-card{border-radius:15px;transition:.25s}
    .stat-card:hover{transform:translateY(-3px)}
    .stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center}
    .recent-activity{max-height:400px;overflow-y:auto}
    .activity-item{padding:1rem;border-left:3px solid #206bc4;margin-bottom:1rem;background:#f8f9fa;border-radius:0 8px 8px 0}
    .chart-container{position:relative;height:360px}
    .stit-dashboard-footer{text-align:center!important}
    .stit-dashboard-footer .container-xl{display:flex;justify-content:center}
    .stit-dashboard-footer .stit-footer-content{width:100%;text-align:center;padding:.75rem 0}
    .stit-dashboard-footer .stit-footer-title{font-weight:600;color:var(--tblr-body-color)}
    .stit-dashboard-footer .stit-footer-copy{font-size:.875rem;color:var(--tblr-secondary)}
</style>
@endsection

@section('content')

{{-- STRUKTUR KARTU INI DISAMAKAN DENGAN DASHBOARD ADMIN --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0"><img src="{{ $user->photo }}" alt="Profile" class="rounded-circle" style="width:64px;height:64px;object-fit:cover;"></div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="mb-1">Selamat datang, {{ $user->name }}!</h4>
                        <p class="text-muted mb-0">Berikut ringkasan aktivitas dan data akademik STIT Darul Ilmi Tasikmalaya.</p>
                    </div>
                    <div class="flex-shrink-0"><span class="badge bg-primary">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JADWAL LANGSUNG TERLIHAT SETELAH LOGIN --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-1">Jadwal Saya</h5>
            <div class="text-muted small">Jadwal perkuliahan yang ditugaskan kepada Anda</div>
        </div>
        <a href="{{ route('dosen.akademik.jadwal') }}" class="btn btn-primary btn-sm">Kelola Jadwal</a>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Tanggal</th><th>Hari</th><th>Jam</th><th>Mata Kuliah</th><th>Kelas</th><th>Ruang</th><th>Metode</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwalSaya as $item)
                    @php
                        $tanggal = data_get($item, 'date') ?? data_get($item, 'tanggal') ?? data_get($item, 'schedule_date');
                        $hari = data_get($item, 'day') ?? data_get($item, 'hari');
                        $mulai = data_get($item, 'waktuKuliah.start') ?? data_get($item, 'waktuKuliah.jam_mulai') ?? data_get($item, 'waktuKuliah.start_time') ?? data_get($item, 'start_time');
                        $selesai = data_get($item, 'waktuKuliah.end') ?? data_get($item, 'waktuKuliah.jam_selesai') ?? data_get($item, 'waktuKuliah.end_time') ?? data_get($item, 'end_time');
                        $kelas = $item->kelas->pluck('name')->filter()->join(', ');
                        $metode = data_get($item, 'method') ?? data_get($item, 'metode') ?? data_get($item, 'jenisKelas.name') ?? '-';
                    @endphp
                    <tr>
                        <td>{{ $tanggal ? \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                        <td>{{ $hari ?: ($tanggal ? \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l') : '-') }}</td>
                        <td>{{ $mulai || $selesai ? trim(($mulai ?: '-') . ' - ' . ($selesai ?: '-')) : '-' }}</td>
                        <td><strong>{{ $item->mataKuliah->name ?? '-' }}</strong></td>
                        <td>{{ $kelas ?: '-' }}</td>
                        <td>{{ $item->ruang->name ?? '-' }}</td>
                        <td><span class="badge bg-blue-lt">{{ $metode }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada jadwal kuliah yang ditugaskan kepada Anda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card bg-primary text-white h-100"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-1">Mahasiswa Terjangkau</h6><h2 class="mb-0">{{ number_format($totalStudents) }}</h2></div><div class="stat-icon bg-white bg-opacity-25"><i class="fas fa-users fa-2x"></i></div></div><div class="mt-3 small">Mahasiswa pada kelas yang Anda ajar</div></div></div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card bg-success text-white h-100"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-1">Mata Kuliah</h6><h2 class="mb-0">{{ number_format($activeCourses) }}</h2></div><div class="stat-icon bg-white bg-opacity-25"><i class="fas fa-book fa-2x"></i></div></div><div class="mt-3 small">Mata kuliah yang Anda ampu</div></div></div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card bg-warning text-white h-100"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-1">KRS Menunggu</h6><h2 class="mb-0">{{ number_format($krsPending) }}</h2></div><div class="stat-icon bg-white bg-opacity-25"><i class="fas fa-file-signature fa-2x"></i></div></div><div class="mt-3 small">Pengajuan yang perlu ditinjau</div></div></div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card bg-info text-white h-100"><div class="card-body"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-1">Jadwal Kuliah</h6><h2 class="mb-0">{{ number_format($totalEvents) }}</h2></div><div class="stat-icon bg-white bg-opacity-25"><i class="fas fa-calendar-alt fa-2x"></i></div></div><div class="mt-3 small">Jadwal perkuliahan Anda</div></div></div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="card-title mb-0">Mata Kuliah Berdasarkan Program Studi</h5></div>
            <div class="card-body"><div class="chart-container"><canvas id="courseDistributionChart"></canvas></div></div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="card-title mb-0">Aktivitas Terbaru</h5></div>
            <div class="card-body recent-activity">
                @forelse($activities as $activity)
                    <div class="activity-item">
                        <div class="d-flex justify-content-between"><h6 class="mb-1"><i class="fas {{ $activity['icon'] }} me-2 text-primary"></i>{{ $activity['title'] }}</h6><small class="text-muted">{{ optional($activity['time'])->diffForHumans() }}</small></div>
                        <p class="mb-0 text-muted">{{ $activity['description'] }}</p>
                    </div>
                @empty
                    <div class="text-center py-4"><i class="fas fa-history fa-2x text-muted mb-2"></i><p class="text-muted mb-0">Belum ada aktivitas terbaru.</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('courseDistributionChart');
    if (el && typeof Chart !== 'undefined') {
        new Chart(el.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($distribution->keys()->values()),
                datasets: [{ label: 'Mata Kuliah', data: @json($distribution->values()->values()) }]
            },
            options: { responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } } }
        });
    }

    const oldFooter = document.querySelector('footer.footer');
    if (oldFooter) {
        oldFooter.classList.add('stit-dashboard-footer');
        oldFooter.innerHTML = `
            <div class="container-xl">
                <div class="stit-footer-content">
                    <div class="stit-footer-title">STIT Darul Ilmi Tasikmalaya</div>
                    <div class="stit-footer-copy">Copyright © ${new Date().toLocaleDateString('id-ID', {month:'long', year:'numeric'})} STIT Darul Ilmi. All rights reserved.</div>
                </div>
            </div>`;
    }

    document.querySelectorAll('.settings').forEach(el => el.remove());
});
</script>
@endsection
