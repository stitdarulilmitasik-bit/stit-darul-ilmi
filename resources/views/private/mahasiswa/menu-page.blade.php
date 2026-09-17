@extends('core-themes.core-backpage')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><h2 class="card-title">{{ $title }}</h2></div>
        <div class="card-body">
            <p class="text-muted">{{ $message }}</p>
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
