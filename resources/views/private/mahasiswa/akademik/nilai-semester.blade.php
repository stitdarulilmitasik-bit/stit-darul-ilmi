@extends('core-themes.core-backpage')
@section('content')
<div class="container-fluid">
<div class="d-flex mb-3"><div><h2 class="page-title">Nilai Semester</h2><div class="text-muted">{{ $semester->name ?? '-' }}</div></div><div class="ms-auto"><a class="btn btn-outline-primary" href="{{ route('mahasiswa.akademik.nilai') }}">← Semua Nilai</a></div></div>
<div class="card"><div class="table-responsive"><table class="table card-table table-vcenter">
<thead><tr><th>No</th><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Nilai</th></tr></thead>
<tbody>
@forelse($nilai as $i=>$n)<tr><td>{{ $i+1 }}</td><td>{{ $n->mataKuliah->code ?? '-' }}</td><td>{{ $n->mataKuliah->name ?? '-' }}</td><td>{{ $n->mataKuliah->sks ?? 0 }}</td><td>{{ $n->nilai_angka ?? '-' }}</td></tr>
@empty<tr><td colspan="5" class="text-center py-4">Belum ada nilai.</td></tr>@endforelse
</tbody></table></div><div class="card-footer">IPS: <strong>{{ $ips }}</strong> · Total SKS: <strong>{{ $totalSks }}</strong></div></div>
</div>
@endsection
