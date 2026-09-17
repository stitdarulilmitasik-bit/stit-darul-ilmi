@extends('core-themes.core-backpage')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Surat Keterangan Aktif Kuliah</h2>
                <div class="text-secondary">Lengkapi data penandatangan dan surat, kemudian cetak dalam format PDF A4.</div>
            </div>
        </div>
        <form method="POST" action="{{ route('mahasiswa.layanan.ajukan-surat-aktif') }}">
            @csrf
            <div class="card-body">
                @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

                <h3 class="mb-3">Data Surat</h3>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Nomor Surat</label><input class="form-control" name="nomor_surat" value="{{ old('nomor_surat') }}" placeholder="Otomatis jika dikosongkan"></div>
                    <div class="col-md-6"><label class="form-label">Tanggal Surat</label><input type="date" class="form-control" name="tanggal_surat" value="{{ old('tanggal_surat', now()->format('Y-m-d')) }}" required></div>
                </div>

                <h3 class="mt-4 mb-3">Penanda Tangan</h3>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Jabatan Penanda Tangan</label>
                        <select class="form-select" name="pejabat_jabatan" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatanDosen as $jabatan)
                                <option value="{{ $jabatan->name }}" @selected(old('pejabat_jabatan') === $jabatan->name)>{{ $jabatan->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-hint">Sumber: Master Jabatan Dosen.</div>
                    </div>
                    <div class="col-md-4"><label class="form-label">Nama Penanda Tangan</label><input class="form-control" name="pejabat_nama" value="{{ old('pejabat_nama') }}" required></div>
                    <div class="col-md-4"><label class="form-label">NIP/NIK</label><input class="form-control" name="pejabat_nip" value="{{ old('pejabat_nip') }}" required></div>
                </div>

                <h3 class="mt-4 mb-3">Data Mahasiswa</h3>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Nama</label><input class="form-control" value="{{ $user->name }}" readonly></div>
                    <div class="col-md-6"><label class="form-label">NIK</label><input class="form-control" name="nik" value="{{ old('nik', $user->nik ?? '') }}" required></div>
                    <div class="col-md-6"><label class="form-label">NIM</label><input class="form-control" value="{{ $user->numb_nim }}" readonly></div>
                    <div class="col-md-6"><label class="form-label">Tempat, Tanggal Lahir</label><input class="form-control" name="ttl" value="{{ old('ttl', trim(($user->birth_place ?? '').(($user->birth_place && $user->birth_date) ? ', ' : '').($user->birth_date ?? ''))) }}" required></div>
                    <div class="col-12"><label class="form-label">Alamat</label><textarea class="form-control" name="alamat" rows="2" required>{{ old('alamat', $user->address ?? '') }}</textarea></div>
                </div>

                <h3 class="mt-4 mb-3">Status Akademik</h3>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Jenjang</label><input class="form-control" name="jenjang" value="{{ old('jenjang', $user->programStudi->jenjang ?? '') }}" required></div>
                    <div class="col-md-8"><label class="form-label">Program Studi</label><input class="form-control" name="program_studi" value="{{ old('program_studi', $user->programStudi->name ?? '') }}" required></div>
                    <div class="col-md-4"><label class="form-label">Memulai Studi</label><select class="form-select" name="periode_mulai" required><option value="Ganjil" @selected(old('periode_mulai','Ganjil')==='Ganjil')>Ganjil</option><option value="Genap" @selected(old('periode_mulai')==='Genap')>Genap</option></select></div>
                    <div class="col-md-4"><label class="form-label">Tahun Akademik</label><input class="form-control" name="tahun_akademik" value="{{ old('tahun_akademik', optional($user->tahunAkademikRegistrasi)->name ?? '') }}" placeholder="Contoh: 2026/2027" required></div>
                    <div class="col-md-4"><label class="form-label">Keperluan</label><input class="form-control" name="keperluan" value="{{ old('keperluan') }}" placeholder="Contoh: Beasiswa" required></div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('mahasiswa.layanan.surat-keterangan') }}" class="btn">Kembali</a>
                <button class="btn btn-primary">Ajukan & Cetak PDF</button>
            </div>
        </form>
    </div>
</div>
@endsection
