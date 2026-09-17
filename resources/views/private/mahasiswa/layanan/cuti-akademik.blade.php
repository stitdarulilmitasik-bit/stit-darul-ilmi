@extends('core-themes.core-backpage')

@section('custom-css')
<style>
    .cuti-hero { background: linear-gradient(135deg, #f8fafc 0%, #eef5ff 100%); border: 1px solid #e5e7eb; }
    .status-badge { min-width: 90px; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <div><strong>Berhasil.</strong> {{ session('success') }}</div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Periksa kembali formulir.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card cuti-hero mb-3">
        <div class="card-body py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-blue-lt mb-2">LAYANAN MAHASISWA</span>
                    <h2 class="mb-2">Pengajuan Cuti Akademik</h2>
                    <p class="text-secondary mb-0">Ajukan cuti akademik melalui SIAKAD. Pengajuan akan diverifikasi oleh bagian akademik sebelum mendapatkan keputusan.</p>
                </div>
                <div class="col-lg-4 mt-3 mt-lg-0 text-lg-end">
                    <div class="text-secondary small">Mahasiswa</div>
                    <div class="fw-bold">{{ $user->name }}</div>
                    <div class="text-secondary">NIM: {{ $user->numb_nim ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cards">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><h3 class="card-title">Form Pengajuan Cuti</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('mahasiswa.layanan.ajukan-cuti') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required">Semester Cuti</label>
                            <input type="text" name="semester" class="form-control" value="{{ old('semester') }}" placeholder="Contoh: 3 / Ganjil 2026-2027" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Alasan Cuti</label>
                            <textarea name="alasan" class="form-control" rows="5" maxlength="2000" required placeholder="Jelaskan alasan pengajuan cuti akademik...">{{ old('alasan') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Selama Cuti</label>
                            <textarea name="alamat_selama_cuti" class="form-control" rows="2" maxlength="500" placeholder="Alamat yang dapat dihubungi selama cuti">{{ old('alamat_selama_cuti') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="no_telepon" class="form-control" value="{{ old('no_telepon', $user->phone ?? '') }}" maxlength="30">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dokumen Pendukung</label>
                                <input type="file" name="file_pendukung" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-hint">PDF/JPG/PNG, maksimal 2 MB.</div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                            Ajukan Cuti Akademik
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header"><h3 class="card-title">Riwayat Pengajuan</h3></div>
                <div class="card-body">
                    @forelse($pengajuan as $item)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between gap-2">
                                <div>
                                    <div class="fw-bold">Semester {{ $item->semester }}</div>
                                    <div class="text-secondary small">Diajukan {{ optional($item->tanggal_pengajuan)->format('d/m/Y') ?? '-' }}</div>
                                </div>
                                @php
                                    $badge = ['Diajukan'=>'bg-blue-lt','Diproses'=>'bg-yellow-lt','Disetujui'=>'bg-green-lt','Ditolak'=>'bg-red-lt'][$item->status] ?? 'bg-secondary-lt';
                                @endphp
                                <span class="badge {{ $badge }} status-badge">{{ $item->status }}</span>
                            </div>
                            <div class="mt-2 small"><strong>Alasan:</strong> {{ $item->alasan }}</div>
                            @if($item->catatan_admin)
                                <div class="alert alert-info mt-2 mb-0 py-2"><strong>Catatan akademik:</strong> {{ $item->catatan_admin }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="avatar avatar-lg bg-blue-lt mb-3">CA</div>
                            <h3>Belum Ada Pengajuan</h3>
                            <p class="text-secondary mb-0">Belum ada pengajuan cuti akademik. Silakan isi formulir di samping.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
