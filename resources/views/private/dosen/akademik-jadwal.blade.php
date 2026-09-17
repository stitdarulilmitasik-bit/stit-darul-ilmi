@extends('core-themes.core-backpage')
@section('content')
<div class="container-xl py-3">
    <div class="d-flex justify-content-between align-items-center mb-3"><div><h2>Jadwal Kuliah</h2><div class="text-muted">Kelola jadwal yang Anda ampu.</div></div></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="card mb-4"><div class="card-header"><h3 class="card-title">Tambah Jadwal</h3></div><div class="card-body">
    <form method="POST" action="{{ route('dosen.akademik.jadwal.store') }}">@csrf
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">Mata Kuliah</label><select name="matkul_id" class="form-select" required><option value="">Pilih</option>@foreach($mata_kuliah as $m)<option value="{{ $m->id }}">{{ $m->code }} - {{ $m->name }}</option>@endforeach</select></div>
      <div class="col-md-6"><label class="form-label">Ruang</label><select name="ruang_id" class="form-select" required>@foreach($ruang as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach</select></div>
      <div class="col-md-4"><label class="form-label">Jenis Kelas</label><select name="jenis_kelas_id" class="form-select" required>@foreach($jenis_kelas as $j)<option value="{{ $j->id }}">{{ $j->name }}</option>@endforeach</select></div>
      <div class="col-md-4"><label class="form-label">Waktu Kuliah</label><select name="waktu_kuliah_id" class="form-select" required>@foreach($waktu_kuliah as $w)<option value="{{ $w->id }}">{{ $w->name ?? ($w->hari.' '.$w->jam_mulai.'-'.$w->jam_selesai) }}</option>@endforeach</select></div>
      <div class="col-md-4"><label class="form-label">Hari</label><input name="hari" class="form-control" placeholder="Senin" required></div>
      <div class="col-md-3"><label class="form-label">SKS</label><input name="bsks" type="number" min="1" max="6" class="form-control" required></div>
      <div class="col-md-3"><label class="form-label">Pertemuan</label><input name="pertemuan" type="number" min="1" class="form-control" value="1" required></div>
      <div class="col-md-3"><label class="form-label">Tanggal</label><input name="tanggal" type="date" class="form-control" required></div>
      <div class="col-md-3"><label class="form-label">Metode</label><select name="metode" class="form-select"><option>Tatap Muka</option><option>Teleconference</option></select></div>
      <div class="col-md-6"><label class="form-label">Kelas</label><select name="kelas_ids[]" class="form-select" multiple required>@foreach($kelas as $k)<option value="{{ $k->id }}">{{ $k->name }}</option>@endforeach</select></div>
      <div class="col-md-6"><label class="form-label">Link Perkuliahan (opsional)</label><input name="link" type="url" class="form-control"></div>
    </div><button class="btn btn-primary mt-3">Simpan Jadwal</button></form></div></div>
    <div class="card"><div class="card-header"><h3 class="card-title">Jadwal Saya</h3></div><div class="table-responsive"><table class="table card-table"><thead><tr><th>Mata Kuliah</th><th>Hari</th><th>Kelas</th><th>Ruang</th><th>Metode</th></tr></thead><tbody>@forelse($jadwal as $j)<tr><td>{{ $j->mataKuliah->name ?? '-' }}</td><td>{{ $j->hari }}</td><td>{{ $j->kelas->pluck('name')->join(', ') }}</td><td>{{ $j->ruang->name ?? '-' }}</td><td>{{ $j->metode }}</td></tr>@empty<tr><td colspan="5" class="text-center text-muted py-4">Belum ada jadwal.</td></tr>@endforelse</tbody></table></div></div>
</div>
@endsection
