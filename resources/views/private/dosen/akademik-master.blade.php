@extends('core-themes.core-backpage')

@section('custom-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .bg-light-primary { background-color: rgba(67, 94, 190, 0.1); }
    .bg-light-success { background-color: rgba(40, 167, 69, 0.1); }
    .bg-light-warning { background-color: rgba(255, 193, 7, 0.1); }
    .bg-light-info { background-color: rgba(23, 162, 184, 0.1); }
    .card { border: none; box-shadow: 0 0 10px rgba(0,0,0,.05); border-radius: 10px; }
    .card-header { background: none; border-bottom: 1px solid rgba(0,0,0,.05); padding: 1.25rem 1.5rem; }
    .card-body { padding: 1.5rem; }
    .table { margin-bottom: 0; }
    .table thead th { border-top: none; border-bottom: 2px solid rgba(0,0,0,.05); font-weight: 600; color: #6c757d; padding: 1rem .75rem .75rem; }
    .table td { vertical-align: middle; padding: .75rem; }
    .table th.text-center, .table td.text-center { text-align: center !important; }
    .master-card { transition: transform .15s ease, box-shadow .15s ease; }
    .master-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    .master-icon { width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 18px; }
    .section-title { font-weight: 600; }
    .readonly-badge { font-size: .75rem; }
    @media (max-width: 768px) {
        .table-responsive table, .table-responsive thead, .table-responsive tbody, .table-responsive th, .table-responsive td, .table-responsive tr { display: block; width: 100%; }
        .table-responsive thead { display: none; }
        .table-responsive tr { margin-bottom: 1rem; border-bottom: 2px solid #eee; }
        .table-responsive td { position: relative; padding-left: 48%; text-align: left !important; border: none; border-bottom: 1px solid #eee; min-height: 42px; }
        .table-responsive td:before { position: absolute; top: .75rem; left: 0; width: 45%; padding-left: .75rem; white-space: nowrap; font-weight: 600; color: #888; content: attr(data-label); }
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">Master Akademik</h2>
                <div class="text-secondary">Data akademik kampus dalam tampilan yang selaras dengan Master Akademik Web Administrator.</div>
            </div>
            <span class="badge bg-light-info text-info readonly-badge mt-2 mt-md-0"><i class="fas fa-eye me-1"></i> Mode Lihat</span>
        </div>
    </div>
</div>

<div class="row mb-4">
    @php
        $summary = [
            ['label' => 'Tahun Akademik', 'count' => $taka->count(), 'icon' => 'fa-calendar-days', 'class' => 'primary'],
            ['label' => 'Fakultas', 'count' => $fakultas->count(), 'icon' => 'fa-building-columns', 'class' => 'success'],
            ['label' => 'Program Studi', 'count' => $prodi->count(), 'icon' => 'fa-graduation-cap', 'class' => 'warning'],
            ['label' => 'Kurikulum', 'count' => $kurikulum->count(), 'icon' => 'fa-book-open', 'class' => 'info'],
            ['label' => 'Mata Kuliah', 'count' => $matakuliah->count(), 'icon' => 'fa-book', 'class' => 'primary'],
            ['label' => 'Jenis Kelas', 'count' => $jenisKelas->count(), 'icon' => 'fa-layer-group', 'class' => 'success'],
            ['label' => 'Kelas', 'count' => $kelas->count(), 'icon' => 'fa-users', 'class' => 'warning'],
            ['label' => 'Waktu Kuliah', 'count' => $waktuKuliah->count(), 'icon' => 'fa-clock', 'class' => 'info'],
            ['label' => 'Jadwal Kuliah', 'count' => $jadwal->count(), 'icon' => 'fa-calendar-check', 'class' => 'primary'],
        ];
    @endphp
    @foreach($summary as $item)
        <div class="col-6 col-md-4 col-xl-3 mb-3">
            <div class="card master-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-secondary">{{ $item['label'] }}</div>
                            <div class="h2 mb-0 mt-1">{{ $item['count'] }}</div>
                        </div>
                        <div class="ms-auto master-icon bg-light-{{ $item['class'] }} text-{{ $item['class'] }}">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="alert alert-info mb-4">
    <div class="d-flex">
        <div class="me-2"><i class="fas fa-circle-info"></i></div>
        <div><strong>Informasi:</strong> Master Akademik Dosen menggunakan struktur data yang sama dengan Master Akademik Administrator, tetapi seluruh data di halaman ini bersifat <strong>lihat saja</strong>. Perubahan data dilakukan oleh Web Administrator.</div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 section-title"><i class="fas fa-calendar-days me-2 text-primary"></i>Tahun Akademik</h5>
                <span class="badge bg-light-primary text-primary">{{ $taka->count() }} Data</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th class="text-center">No</th><th>Tahun Akademik</th><th class="text-center">Semester</th><th>Periode</th><th class="text-center">Status</th></tr></thead>
                        <tbody>
                        @forelse($taka as $key => $item)
                            <tr>
                                <td data-label="No" class="text-center">{{ $key + 1 }}</td>
                                <td data-label="Tahun Akademik"><strong>{{ $item->name }}</strong></td>
                                <td data-label="Semester" class="text-center"><span class="badge bg-light-primary text-primary">{{ $item->type }}</span></td>
                                <td data-label="Periode"><small>{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M Y') : '-' }} - {{ $item->ended_date ? \Carbon\Carbon::parse($item->ended_date)->format('d M Y') : '-' }}</small></td>
                                <td data-label="Status" class="text-center"><span class="badge {{ $item->status === 'Aktif' ? 'bg-light-success text-success' : 'bg-light-warning text-warning' }}">{{ $item->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada data tahun akademik.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-12 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-circle-info me-2 text-info"></i>Informasi Akademik</h5></div>
            <div class="card-body">
                <p class="text-secondary">Halaman ini menampilkan referensi akademik yang digunakan dalam sistem SIAKAD.</p>
                @php $aktif = $taka->where('status', 'Aktif')->take(3); @endphp
                <h6 class="mb-3">Tahun Akademik Aktif</h6>
                @forelse($aktif as $item)
                    <div class="list-group-item px-0 border-0 border-bottom">
                        <div class="d-flex justify-content-between"><strong>{{ $item->name }}</strong><span class="badge bg-light-success text-success">{{ $item->type }}</span></div>
                        <small class="text-secondary">{{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M Y') : '-' }} - {{ $item->ended_date ? \Carbon\Carbon::parse($item->ended_date)->format('d M Y') : '-' }}</small>
                    </div>
                @empty
                    <div class="text-secondary">Belum ada tahun akademik aktif.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0 section-title"><i class="fas fa-building-columns me-2 text-success"></i>Fakultas</h5></div>
            <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Kode</th><th>Fakultas</th><th>Status</th></tr></thead><tbody>
                @forelse($fakultas as $key => $row)<tr><td data-label="No">{{ $key + 1 }}</td><td data-label="Kode">{{ $row->code }}</td><td data-label="Fakultas"><strong>{{ $row->name }}</strong></td><td data-label="Status">{{ $row->status ?? '-' }}</td></tr>@empty<tr><td colspan="4" class="text-center text-secondary py-4">Belum ada data.</td></tr>@endforelse
            </tbody></table></div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0 section-title"><i class="fas fa-graduation-cap me-2 text-warning"></i>Program Studi</h5></div>
            <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Kode</th><th>Program Studi</th><th>Fakultas</th></tr></thead><tbody>
                @forelse($prodi as $key => $row)<tr><td data-label="No">{{ $key + 1 }}</td><td data-label="Kode">{{ $row->code }}</td><td data-label="Program Studi"><strong>{{ $row->name }}</strong></td><td data-label="Fakultas">{{ optional($row->fakultas)->name ?? '-' }}</td></tr>@empty<tr><td colspan="4" class="text-center text-secondary py-4">Belum ada data.</td></tr>@endforelse
            </tbody></table></div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0 section-title"><i class="fas fa-book-open me-2 text-info"></i>Kurikulum</h5></div>
    <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Kode</th><th>Nama Kurikulum</th><th>Status</th></tr></thead><tbody>
        @forelse($kurikulum as $key => $row)<tr><td data-label="No">{{ $key + 1 }}</td><td data-label="Kode">{{ $row->code }}</td><td data-label="Nama Kurikulum"><strong>{{ $row->name }}</strong></td><td data-label="Status">{{ $row->status ?? '-' }}</td></tr>@empty<tr><td colspan="4" class="text-center text-secondary py-4">Belum ada data.</td></tr>@endforelse
    </tbody></table></div>
</div>

<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0 section-title"><i class="fas fa-book me-2 text-primary"></i>Mata Kuliah</h5></div>
    <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Kode</th><th>Mata Kuliah</th><th>Program Studi</th><th>Kurikulum</th><th class="text-center">SKS</th><th>Status</th></tr></thead><tbody>
        @forelse($matakuliah as $key => $row)<tr><td data-label="No">{{ $key + 1 }}</td><td data-label="Kode">{{ $row->code }}</td><td data-label="Mata Kuliah"><strong>{{ $row->name }}</strong></td><td data-label="Program Studi">{{ optional($row->programStudi)->name ?? '-' }}</td><td data-label="Kurikulum">{{ optional($row->kurikulum)->name ?? '-' }}</td><td data-label="SKS" class="text-center">{{ $row->bsks ?? $row->sks ?? '-' }}</td><td data-label="Status">{{ $row->status ?? '-' }}</td></tr>@empty<tr><td colspan="7" class="text-center text-secondary py-4">Belum ada data.</td></tr>@endforelse
    </tbody></table></div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0 section-title"><i class="fas fa-layer-group me-2 text-success"></i>Jenis Kelas</h5></div>
            <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Kode</th><th>Jenis Kelas</th><th>Status</th></tr></thead><tbody>
                @forelse($jenisKelas as $key => $row)<tr><td data-label="No">{{ $key + 1 }}</td><td data-label="Kode">{{ $row->code ?? '-' }}</td><td data-label="Jenis Kelas"><strong>{{ $row->name }}</strong></td><td data-label="Status">{{ $row->status ?? '-' }}</td></tr>@empty<tr><td colspan="4" class="text-center text-secondary py-4">Belum ada data.</td></tr>@endforelse
            </tbody></table></div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0 section-title"><i class="fas fa-users me-2 text-warning"></i>Kelas</h5></div>
            <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Kode</th><th>Kelas</th><th>Program Studi</th><th>Jenis</th></tr></thead><tbody>
                @forelse($kelas as $key => $row)<tr><td data-label="No">{{ $key + 1 }}</td><td data-label="Kode">{{ $row->code }}</td><td data-label="Kelas"><strong>{{ $row->name ?? '-' }}</strong></td><td data-label="Program Studi">{{ optional($row->programStudi)->name ?? '-' }}</td><td data-label="Jenis">{{ optional($row->jenisKelas)->name ?? '-' }}</td></tr>@empty<tr><td colspan="5" class="text-center text-secondary py-4">Belum ada data.</td></tr>@endforelse
            </tbody></table></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0 section-title"><i class="fas fa-clock me-2 text-info"></i>Waktu Kuliah</h5></div>
            <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Waktu</th><th>Jenis Kelas</th></tr></thead><tbody>
                @forelse($waktuKuliah as $key => $row)<tr><td data-label="No">{{ $key + 1 }}</td><td data-label="Waktu"><strong>{{ $row->name ?? ($row->jam ?? $row->time ?? '-') }}</strong></td><td data-label="Jenis Kelas">{{ optional($row->jenisKelas)->name ?? '-' }}</td></tr>@empty<tr><td colspan="3" class="text-center text-secondary py-4">Belum ada data.</td></tr>@endforelse
            </tbody></table></div>
        </div>
    </div>
    <div class="col-lg-7 mb-4">
        <div class="card h-100">
            <div class="card-header"><h5 class="mb-0 section-title"><i class="fas fa-calendar-check me-2 text-primary"></i>Jadwal Kuliah</h5></div>
            <div class="table-responsive"><table class="table"><thead><tr><th>No</th><th>Kode</th><th>Mata Kuliah</th><th>Hari</th><th>Jam</th><th>Ruang</th></tr></thead><tbody>
                @forelse($jadwal as $key => $row)
                    @php
                        $waktu = $row->waktuKuliah;
                        $jam = $row->jam ?? $row->time ?? null;
                        if (!$jam && $waktu) { $jam = $waktu->name ?? $waktu->jam ?? $waktu->time ?? null; }
                    @endphp
                    <tr><td data-label="No">{{ $key + 1 }}</td><td data-label="Kode">{{ $row->code }}</td><td data-label="Mata Kuliah"><strong>{{ optional($row->mataKuliah)->name ?? '-' }}</strong></td><td data-label="Hari">{{ $row->hari ?? $row->day ?? '-' }}</td><td data-label="Jam">{{ $jam ?? '-' }}</td><td data-label="Ruang">{{ optional($row->ruang)->name ?? '-' }}</td></tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada data jadwal kuliah.</td></tr>
                @endforelse
            </tbody></table></div>
        </div>
    </div>
</div>
@endsection
