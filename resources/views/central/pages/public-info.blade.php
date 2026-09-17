@extends('core-themes.core-mainpage')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-transparent py-4">
                    <h1 class="card-title mb-1">{{ $info['heading'] }}</h1>
                    <div class="text-muted">{{ $webs->school_name ?? 'Perguruan Tinggi' }}</div>
                </div>
                <div class="card-body py-4">
                    @if(isset($info['vision']))
                        <h2 class="h3">Visi</h2>
                        <p class="fs-3 mb-5">{{ $info['vision'] }}</p>
                        <h2 class="h3">Misi</h2>
                        <ol class="fs-4 lh-lg">
                            @foreach($info['missions'] as $mission)
                                <li>{{ $mission }}</li>
                            @endforeach
                        </ol>
                    @elseif(isset($info['structure']))
                        <h2 class="h3 mb-4">Struktur Organisasi</h2>
                        <div class="row g-3">
                            @foreach($info['structure'] as $i => $unit)
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body d-flex align-items-center">
                                            <span class="badge bg-primary me-3">{{ $i + 1 }}</span>
                                            <strong>{{ $unit }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="fs-4 lh-lg">{!! nl2br(e($info['content'] ?? 'Informasi belum tersedia.')) !!}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
