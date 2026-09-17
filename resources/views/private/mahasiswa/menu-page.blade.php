@extends('core-themes.core-backpage')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">{{ $title }}</h2>
        </div>
        <div class="card-body">
            <p class="text-muted">{{ $message }}</p>

            @if($title === 'Surat Keterangan')
                <div class="row g-3 mt-2">
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('mahasiswa.layanan.surat-aktif-kuliah') }}" class="card card-link card-link-pop h-100 text-decoration-none">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="avatar avatar-lg bg-primary-lt me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M5 12v-7a2 2 0 0 1 2 -2h6l6 6v10a2 2 0 0 1 -2 2h-6"/><path d="M9 17h6"/><path d="M9 13h6"/></svg>
                                    </span>
                                    <div>
                                        <h3 class="card-title mb-1">Surat Keterangan Aktif Kuliah</h3>
                                        <div class="text-secondary">Cetak surat keterangan resmi</div>
                                    </div>
                                </div>
                                <p class="text-secondary mb-0">Surat keterangan bahwa mahasiswa masih aktif mengikuti perkuliahan di STIT Darul Ilmi Tasikmalaya.</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <span class="btn btn-primary">Buat Surat Aktif Kuliah</span>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            @isset($items)
                @if($items->count())
                    <div class="list-group list-group-flush">
                    @foreach($items as $item)
                        <div class="list-group-item px-0">
                            <div class="fw-bold">{{ $item->title ?? $item->name ?? $item->code ?? 'Informasi' }}</div>
                            @if(isset($item->start_date))<div class="text-muted small">{{ $item->start_date }} @if(isset($item->ended_date)) s/d {{ $item->ended_date }} @endif</div>@endif
                        </div>
                    @endforeach
                    </div>
                @endif
            @endisset

            @if($title === 'Bantuan')
                <form method="POST" action="{{ route('mahasiswa.bantuan.kirim-pesan') }}" class="mt-4">
                    @csrf
                    <label class="form-label">Pesan/Kendala</label>
                    <textarea name="pesan" class="form-control" rows="4" required></textarea>
                    <button class="btn btn-primary mt-3">Kirim Pesan</button>
                </form>
            @endif
        </div>
    </div>
</div>

@if(isset($nilai))
<div class="card mt-3"><div class="card-header d-flex justify-content-between"><h3 class="card-title">Ringkasan Transkrip</h3><a class="btn btn-primary" href="{{ route('mahasiswa.layanan.transkrip.cetak') }}">Cetak PDF</a></div><div class="card-body"><div class="row mb-3"><div class="col-md-3">Total SKS<br><strong>{{ $totalSks }}</strong></div><div class="col-md-3">IPK<br><strong>{{ number_format($ipk,2) }}</strong></div></div><div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Semester</th><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Nilai</th></tr></thead><tbody>@foreach($nilai as $n)<tr><td>{{ $n->semester }}</td><td>{{ $n->mataKuliah->code ?? '-' }}</td><td>{{ $n->mataKuliah->name ?? '-' }}</td><td>{{ $n->sks }}</td><td>{{ $n->nilai_huruf ?? '-' }}</td></tr>@endforeach</tbody></table></div></div></div>
@endif
@endsection
