@extends('core-themes.core-mainpage')

@section('custom-css')
<style>
.cms-hero{border-radius:28px;overflow:hidden;background:linear-gradient(135deg,#0b5ed7,#163d7a);color:#fff}.cms-hero .logo{max-height:230px;object-fit:contain}.cms-card{height:100%;border:0;border-radius:18px;box-shadow:0 8px 28px rgba(0,0,0,.07)}.cms-card img{height:190px;object-fit:cover}.cms-title{font-weight:800}.cms-section{padding:1.75rem 0}.cms-section:first-of-type{padding-top:0}.cms-section:last-of-type{padding-bottom:1rem}.cms-section + .cms-section{margin-top:0}.agenda{border-left:4px solid var(--tblr-primary);padding-left:1rem;margin-bottom:1.25rem}.admin-note{font-size:.8rem;opacity:.7}
</style>
@endsection

@section('content')
<div class="container-xl pt-2 pb-4">
    @php($hero=$sections->firstWhere('section_key','hero'))
    <section class="cms-hero p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="text-uppercase fw-bold small opacity-75 mb-2">Sekolah Tinggi Ilmu Tarbiyah</div>
                <h1 class="display-4 fw-bold mb-3">{{ $hero?->title ?: $webs->school_name }}</h1>
                <p class="fs-3 mb-3">{{ $hero?->subtitle ?: 'STIT Darul Ilmi Tasikmalaya' }}</p>
                @if($hero?->content)<p class="fs-5 opacity-90">{!! nl2br(e($hero->content)) !!}</p>@endif
                @if($hero?->button_url)<a href="{{ $hero->button_url }}" class="btn btn-light btn-lg">{{ $hero->button_text ?: 'Selengkapnya' }}</a>@endif
            </div>
            <div class="col-lg-5 text-center">
                @if($hero?->image)<img src="{{ $hero->image }}" class="img-fluid logo" alt="{{ $hero->title }}">@else<img src="{{ $webs->school_logo_hori }}" class="img-fluid logo" alt="Logo STIT Darul Ilmi">@endif
            </div>
        </div>
    </section>

    @foreach($sections->where('section_key','!=','hero') as $section)
    @if($section->title || $section->subtitle || $section->content || $section->button_url || $section->image)
    <section class="cms-section border-bottom" id="{{ $section->section_key }}">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="text-primary text-uppercase fw-bold small">{{ $webs->school_name }}</div>
                <h2 class="cms-title display-6">{{ $section->title ?: ucfirst(str_replace(['-','_'],' ',$section->section_key)) }}</h2>
                @if($section->subtitle)<p class="fs-4 text-secondary">{{ $section->subtitle }}</p>@endif
                @if($section->content)<div class="fs-5">{!! nl2br(e($section->content)) !!}</div>@endif
                @if($section->button_url)<a href="{{ $section->button_url }}" class="btn btn-primary mt-3">{{ $section->button_text ?: 'Selengkapnya' }}</a>@endif
            </div>
            @if($section->image)<div class="col-lg-4"><img src="{{ $section->image }}" class="img-fluid rounded-4" alt="{{ $section->title }}"></div>@endif
        </div>
    </section>
    @endif
    @endforeach

    <section class="cms-section">
        <div class="row g-4">
            <div class="col-md-4"><div class="card cms-card"><div class="card-body"><div class="text-secondary">Program Studi</div><div class="display-6 fw-bold">{{ $programStudis->count() }}</div></div></div></div>
            <div class="col-md-4"><div class="card cms-card"><div class="card-body"><div class="text-secondary">Berita</div><div class="display-6 fw-bold">{{ $beritas->count() }}</div></div></div></div>
            <div class="col-md-4"><div class="card cms-card"><div class="card-body"><div class="text-secondary">Agenda Akademik</div><div class="display-6 fw-bold">{{ $kalender->count() }}</div></div></div></div>
        </div>
    </section>

    <section class="cms-section pt-0">
        <div class="d-flex justify-content-between align-items-center mb-4"><h2 class="cms-title mb-0">Program Studi</h2><a href="{{ route('root.prodi-index') }}" class="btn btn-outline-primary">Lihat Semua</a></div>
        <div class="row g-4">
            @forelse($programStudis as $prodi)<div class="col-md-6 col-lg-4"><div class="card cms-card"><div class="card-body"><span class="badge bg-primary-lt mb-2">{{ $prodi->level }}</span><h3 class="h4">{{ $prodi->name }}</h3><p class="text-secondary mb-0">{{ $prodi->fakultas?->name }}</p></div></div></div>@empty<div class="col-12"><div class="alert alert-info">Belum ada program studi aktif.</div></div>@endforelse
        </div>
    </section>

    <section class="cms-section pt-0">
        <div class="d-flex justify-content-between align-items-center mb-4"><h2 class="cms-title mb-0">Kalender & Agenda Akademik</h2><a href="{{ route('root.kalender-akademik-index') }}" class="btn btn-outline-primary">Kalender Lengkap</a></div>
        <div class="row g-4">
            <div class="col-lg-8">
                @forelse($kalender as $item)<div class="agenda"><div class="text-primary fw-bold">{{ \Carbon\Carbon::parse($item->start_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</div><h3 class="h4 mb-1">{{ $item->name }}</h3><span class="text-secondary">{{ $item->type }}</span></div>@empty<div class="alert alert-info">Belum ada agenda akademik.</div>@endforelse
            </div>
            <div class="col-lg-4"><div class="card cms-card"><div class="card-body"><h3 class="h4">Pengumuman</h3>@forelse($pengumuman as $item)<div class="mb-3"><a href="{{ route('root.pengumuman-view',$item->slug) }}" class="fw-bold text-decoration-none">{{ $item->name }}</a><div class="small text-secondary">{{ optional($item->created_at)->locale('id')->isoFormat('D MMMM Y') }}</div></div>@empty<p class="text-secondary">Belum ada pengumuman.</p>@endforelse</div></div></div>
        </div>
    </section>

    <section class="cms-section pt-0">
        <div class="d-flex justify-content-between align-items-center mb-4"><h2 class="cms-title mb-0">Berita Terbaru</h2><a href="{{ route('root.berita-index') }}" class="btn btn-outline-primary">Semua Berita</a></div>
        <div class="row g-4">
            @forelse($beritas as $item)<div class="col-md-6 col-lg-3"><div class="card cms-card"><div class="card-body"><div class="small text-primary mb-2">{{ $item->kategori?->name }}</div><h3 class="h5">{{ $item->title ?? $item->name }}</h3><div class="small text-secondary mb-3">{{ optional($item->created_at)->locale('id')->isoFormat('D MMMM Y') }}</div><a href="{{ route('root.berita-view',$item->slug) }}" class="btn btn-sm btn-primary">Baca Berita</a></div></div></div>@empty<div class="col-12"><div class="alert alert-info">Belum ada berita.</div></div>@endforelse
        </div>
    </section>

    <div class="text-center text-secondary admin-note pb-5">Konten section halaman depan dapat dikelola dari Admin Panel → Pengaturan → Front Page.</div>
</div>
@endsection
