@extends('core-themes.core-backpage')

@section('custom-css')
<style>
    .academic-card { transition: transform .15s ease, box-shadow .15s ease; }
    .academic-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    .academic-table th { white-space: nowrap; }
    .academic-table td { vertical-align: middle; }
    .count-badge { font-size: 1.35rem; font-weight: 700; }
</style>
@endsection

@section('content')
<div class="row row-cards mb-4">
    @php
        $summary = [
            ['label' => 'Tahun Akademik', 'count' => $taka->count(), 'icon' => 'calendar'],
            ['label' => 'Program Studi', 'count' => $prodi->count(), 'icon' => 'school'],
            ['label' => 'Mata Kuliah', 'count' => $matakuliah->count(), 'icon' => 'book'],
            ['label' => 'Kelas', 'count' => $kelas->count(), 'icon' => 'users'],
            ['label' => 'Jadwal Kuliah', 'count' => $jadwal->count(), 'icon' => 'clock'],
        ];
    @endphp
    @foreach($summary as $item)
        <div class="col-sm-6 col-lg">
            <div class="card academic-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="text-secondary">{{ $item['label'] }}</div>
                            <div class="count-badge mt-1">{{ $item['count'] }}</div>
                        </div>
                        <div class="ms-auto">
                            <span class="avatar bg-primary-lt">
                                @if($item['icon'] === 'calendar') 📅 @elseif($item['icon'] === 'school') 🎓 @elseif($item['icon'] === 'book') 📚 @elseif($item['icon'] === 'users') 👥 @else 🕒 @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="alert alert-info">
    <strong>Informasi:</strong> Halaman ini bersifat <strong>lihat saja</strong> untuk dosen. Perubahan data Master Akademik dilakukan oleh Web Administrator.
</div>

<div class="card mb-4">
    <div class="card-header"><h3 class="card-title">Tahun Akademik</h3></div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table academic-table">
            <thead><tr><th>Kode</th><th>Nama</th><th>Semester</th><th>Mulai</th><th>Selesai</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($taka as $row)
                <tr><td>{{ $row->code }}</td><td>{{ $row->name }}</td><td>{{ $row->type }}</td><td>{{ $row->start_date }}</td><td>{{ $row->ended_date }}</td><td>{{ $row->status }}</td></tr>
            @empty <tr><td colspan="6" class="text-center text-secondary">Belum ada data.</td></tr> @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row row-cards">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">Program Studi</h3></div>
            <div class="table-responsive"><table class="table table-vcenter card-table academic-table">
                <thead><tr><th>Kode</th><th>Program Studi</th><th>Fakultas</th><th>Status</th></tr></thead>
                <tbody>@forelse($prodi as $row)<tr><td>{{ $row->code }}</td><td>{{ $row->name }}</td><td>{{ optional($row->fakultas)->name ?? '-' }}</td><td>{{ $row->status ?? '-' }}</td></tr>@empty<tr><td colspan="4" class="text-center text-secondary">Belum ada data.</td></tr>@endforelse</tbody>
            </table></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header"><h3 class="card-title">Fakultas</h3></div>
            <div class="table-responsive"><table class="table table-vcenter card-table academic-table">
                <thead><tr><th>Kode</th><th>Fakultas</th><th>Status</th></tr></thead>
                <tbody>@forelse($fakultas as $row)<tr><td>{{ $row->code }}</td><td>{{ $row->name }}</td><td>{{ $row->status ?? '-' }}</td></tr>@empty<tr><td colspan="3" class="text-center text-secondary">Belum ada data.</td></tr>@endforelse</tbody>
            </table></div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header"><h3 class="card-title">Mata Kuliah</h3></div>
    <div class="table-responsive"><table class="table table-vcenter card-table academic-table">
        <thead><tr><th>Kode</th><th>Mata Kuliah</th><th>Program Studi</th><th>Kurikulum</th><th>SKS</th><th>Status</th></tr></thead>
        <tbody>@forelse($matakuliah as $row)<tr><td>{{ $row->code }}</td><td>{{ $row->name }}</td><td>{{ optional($row->programStudi)->name ?? '-' }}</td><td>{{ optional($row->kurikulum)->name ?? '-' }}</td><td>{{ $row->bsks ?? $row->sks ?? '-' }}</td><td>{{ $row->status ?? '-' }}</td></tr>@empty<tr><td colspan="6" class="text-center text-secondary">Belum ada data.</td></tr>@endforelse</tbody>
    </table></div>
</div>

<div class="card mt-4">
    <div class="card-header"><h3 class="card-title">Kelas</h3></div>
    <div class="table-responsive"><table class="table table-vcenter card-table academic-table">
        <thead><tr><th>Kode</th><th>Nama Kelas</th><th>Status</th></tr></thead>
        <tbody>@forelse($kelas as $row)<tr><td>{{ $row->code }}</td><td>{{ $row->name ?? $row->nama ?? '-' }}</td><td>{{ $row->status ?? '-' }}</td></tr>@empty<tr><td colspan="3" class="text-center text-secondary">Belum ada data.</td></tr>@endforelse</tbody>
    </table></div>
</div>

<div class="card mt-4">
    <div class="card-header"><h3 class="card-title">Jadwal Kuliah</h3></div>
    <div class="table-responsive"><table class="table table-vcenter card-table academic-table">
        <thead><tr><th>Kode</th><th>Jadwal</th><th>Hari</th><th>Jam</th><th>Status</th></tr></thead>
        <tbody>@forelse($jadwal as $row)<tr><td>{{ $row->code }}</td><td>{{ $row->name ?? $row->nama ?? '-' }}</td><td>{{ $row->hari ?? $row->day ?? '-' }}</td><td>{{ $row->jam ?? $row->time ?? '-' }}</td><td>{{ $row->status ?? '-' }}</td></tr>@empty<tr><td colspan="5" class="text-center text-secondary">Belum ada data.</td></tr>@endforelse</tbody>
    </table></div>
</div>
@endsection
