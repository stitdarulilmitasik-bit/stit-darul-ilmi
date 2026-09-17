@extends('core-themes.core-backpage')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Surat Keterangan Aktif Kuliah</h2>
                <div class="text-secondary">Lengkapi data surat. Data penanda tangan akan terisi otomatis sesuai jabatan yang dipilih.</div>
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
                        <select class="form-select" id="pejabat_jabatan" name="pejabat_jabatan" required>
                            <option value="">Pilih Jabatan</option>
                            <option value="Ketua STIT Darul Ilmi Tasikmalaya" data-nama="Dr. H. Dudung Rahmat Hidayat, M.Pd." data-nip="12000" @selected(old('pejabat_jabatan') === 'Ketua STIT Darul Ilmi Tasikmalaya')>Ketua STIT Darul Ilmi Tasikmalaya</option>
                            <option value="Wakil Ketua STIT Darul Ilmi Tasikmalaya" data-nama="Aa Sudirman, M.Pd.I" data-nip="12001" @selected(old('pejabat_jabatan') === 'Wakil Ketua STIT Darul Ilmi Tasikmalaya')>Wakil Ketua STIT Darul Ilmi Tasikmalaya</option>
                        </select>
                    </div>
                    <div class="col-md-4"><label class="form-label">Nama Penanda Tangan</label><input id="pejabat_nama" class="form-control" name="pejabat_nama" value="{{ old('pejabat_nama') }}" readonly required></div>
                    <div class="col-md-4"><label class="form-label">NIP/NID</label><input id="pejabat_nip" class="form-control" name="pejabat_nip" value="{{ old('pejabat_nip') }}" readonly required></div>
                </div>
                <div class="form-hint mt-2">Nama dan NIP/NID terisi otomatis berdasarkan jabatan. Nomor sementara: Ketua 12000, Wakil Ketua 12001 dan dapat diperbarui langsung melalui database.</div>

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

@section('custom-js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const jabatan = document.getElementById('pejabat_jabatan');
    const nama = document.getElementById('pejabat_nama');
    const nip = document.getElementById('pejabat_nip');

    function isiPenandaTangan() {
        const option = jabatan.options[jabatan.selectedIndex];
        nama.value = option?.dataset?.nama || '';
        nip.value = option?.dataset?.nip || '';
    }

    jabatan.addEventListener('change', isiPenandaTangan);
    isiPenandaTangan();
});
</script>
@endsection
