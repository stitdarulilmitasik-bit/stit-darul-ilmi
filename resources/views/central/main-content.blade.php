@extends('core-themes.core-mainpage')

@section('custom-css')
<style>
    .hero{border-radius:24px;overflow:hidden;background:linear-gradient(135deg,#0b5ed7,#173f8a);color:#fff}
    .hero img{max-height:360px;object-fit:cover;border-radius:18px}
    .section-title{font-weight:800}.content-card{height:100%;border:0;box-shadow:0 8px 28px rgba(0,0,0,.07);border-radius:18px}.content-card:hover{transform:translateY(-3px);transition:.2s}.muted{color:var(--tblr-secondary)}
    .calendar-item{border-left:4px solid var(--tblr-primary);padding-left:14px;margin-bottom:16px}.stat-card{border-radius:18px;border:0;box-shadow:0 8px 28px rgba(0,0,0,.06)}
</style>
@endsection

@section('content')
<div class="container-xl py-4">
    @php($hero = $homepageSections->firstWhere('section_key','hero'))
    <section class="hero p-4 p-lg-5 mb-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="text-uppercase small fw-bold opacity-75 mb-2">STIT Darul Ilmi Tasikmalaya</div>
                <h1 class="display-4 fw-bold">{{ $hero?->title ?: 'STIT Darul Ilmi Tasikmalaya' }}</h1>
                <p class="fs-4 opacity-90">{{ $hero?->subtitle ?: 'Perguruan tinggi keislaman yang unggul, profesional, dan berintegritas.' }}</p>
                @if($hero?->content)<div class="mb-4">{!! nl2br(e($hero->content)) !!}</div>@endif
                @if($hero?->button_url)<a href="{{ $hero->button_url }}" class="btn btn-light btn-lg">{{ $hero->button_text ?: 'Selengkapnya' }}</a>@endif
            </div>
            <div class="col-lg-5 text-center">
                @if($hero?->image)<img src="{{ $hero->image }}" class="img-fluid w-100" alt="{{ $hero->title }}">@else
                    <img src="{{ $webs->school_logo_hori }}" class="img-fluid p-5" alt="Logo STIT Darul Ilmi">
                @endif
            </div>
        </div>
    </section>

    @foreach($homepageSections->where('section_key','!=','hero') as $section)
        <section class="mb-5" id="{{ $section->section_key }}">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="text-uppercase small text-primary fw-bold">STIT Darul Ilmi</div>
                    <h2 class="section-title mb-2">{{ $section->title }}</h2>
                    @if($section->subtitle)<p class="fs-5 muted">{{ $section->subtitle }}</p>@endif
                    @if($section->content)<div class="text-secondary">{!! nl2br(e($section->content)) !!}</div>@endif
                    @if($section->button_url)<a class="btn btn-primary mt-3" href="{{ $section->button_url }}">{{ $section->button_text ?: 'Selengkapnya' }}</a>@endif
                </div>
                @if($section->image)<div class="col-lg-4"><img src="{{ $section->image }}" class="img-fluid rounded-4" alt="{{ $section->title }}"></div>@endif
            </div>
        </section>
    @endforeach

    <section class="mb-5">
        <div class="row g-4">
            <div class="col-md-4"><div class="card stat-card"><div class="card-body"><div class="text-secondary">Program Studi</div><div class="display-6 fw-bold">{{ $programStudis->count() }}</div></div></div></div>
            <div class="col-md-4"><div class="card stat-card"><div class="card-body"><div class="text-secondary">Berita Terbaru</div><div class="display-6 fw-bold">{{ $beritas->count() }}</div></div></div></div>
            <div class="col-md-4"><div class="card stat-card"><div class="card-body"><div class="text-secondary">Agenda Akademik</div><div class="display-6 fw-bold">{{ $kalender->count() }}</div></div></div></div>
        </div>
    </section>

    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="section-title mb-0">Program Studi</h2><a href="{{ route('root.prodi-index') }}" class="btn btn-outline-primary">Semua Program Studi</a></div>
        <div class="row g-4">
            @forelse($programStudis as $prodi)
                <div class="col-md-6 col-lg-4"><div class="card content-card"><div class="card-body"><div class="badge bg-primary-lt mb-2">{{ $prodi->level }}</div><h3 class="h4">{{ $prodi->name }}</h3><p class="muted mb-0">{{ $prodi->fakultas?->name }}</p></div></div></div>
            @empty <div class="col-12"><div class="alert alert-info">Belum ada program studi aktif.</div></div>@endforelse
        </div>
    </section>

    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="section-title mb-0">Agenda Akademik</h2><a href="{{ route('root.kalender-akademik-index') }}" class="btn btn-outline-primary">Kalender Akademik</a></div>
        <div class="row g-4">
            <div class="col-lg-8">
                @forelse($kalender as $item)
                    <div class="calendar-item"><div class="small text-primary fw-bold">{{ \Carbon\Carbon::parse($item->start_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</div><h3 class="h4 mb-1">{{ $item->name }}</h3><div class="muted">{{ $item->type }}</div></div>
                @empty <div class="alert alert-info">Belum ada agenda akademik.</div>@endforelse
            </div>
            <div class="col-lg-4"><div class="card content-card"><div class="card-body"><h3 class="h4">Informasi</h3>@forelse($pengumuman as $item)<div class="mb-3"><a href="{{ route('root.pengumuman-view',$item->slug) }}" class="fw-bold text-decoration-none">{{ $item->name }}</a><div class="small muted">{{ optional($item->created_at)->locale('id')->isoFormat('D MMMM Y') }}</div></div>@empty<p class="muted">Belum ada pengumuman.</p>@endforelse</div></div></div>
        </div>
    </section>

    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3"><h2 class="section-title mb-0">Berita Terbaru</h2><a href="{{ route('root.berita-index') }}" class="btn btn-outline-primary">Semua Berita</a></div>
        <div class="row g-4">
            @forelse($beritas as $item)<div class="col-md-6 col-lg-3"><div class="card content-card"><div class="card-body"><div class="small text-primary mb-2">{{ $item->kategori?->name }}</div><h3 class="h5">{{ $item->title ?? $item->name }}</h3><p class="small muted">{{ optional($item->created_at)->locale('id')->isoFormat('D MMMM Y') }}</p><a href="{{ route('root.berita-view',$item->slug) }}" class="btn btn-sm btn-primary">Baca</a></div></div></div>@empty<div class="col-12"><div class="alert alert-info">Belum ada berita.</div></div>@endforelse
        </div>
    </section>
</div>
@endsection
