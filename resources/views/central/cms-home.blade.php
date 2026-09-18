@extends('core-themes.core-mainpage')

@section('custom-css')
<style>
:root{
    --stit-green:#8fbc55;
    --stit-green-dark:#4d7c32;
    --stit-cream:#f5f8ec;
    --stit-ink:#20301f;
    --stit-muted:#687467;
    --stit-line:#dfe8d7;
}
body.cms-front-page{
    background:#fbfcf8;
    color:var(--stit-ink);
}
body.cms-front-page .page-body > .container-xl{
    max-width:none!important;
    padding-left:0!important;
    padding-right:0!important;
}
.cms-home{
    overflow:hidden;
}
.cms-home .container-xl{
    max-width:1180px;
}
.cms-nav-space{
    display:none;
}
.cms-hero-wrap{
    padding:28px 20px 0;
}
.cms-hero{
    position:relative;
    min-height:560px;
    border-radius:36px;
    overflow:hidden;
    background:
        radial-gradient(circle at 85% 20%,rgba(183,221,117,.5),transparent 32%),
        linear-gradient(135deg,#eef7df 0%,#f8faef 52%,#e7f1d6 100%);
}
.cms-hero:after{
    content:"";
    position:absolute;
    width:330px;
    height:330px;
    border:1px solid rgba(77,124,50,.15);
    border-radius:50%;
    right:-110px;
    bottom:-120px;
}
.cms-hero-inner{
    min-height:560px;
    position:relative;
    z-index:1;
}
.cms-kicker{
    display:inline-flex;
    align-items:center;
    gap:9px;
    padding:8px 13px;
    border-radius:999px;
    background:#fff;
    color:var(--stit-green-dark);
    font-size:.76rem;
    font-weight:800;
    letter-spacing:.08em;
    text-transform:uppercase;
    box-shadow:0 8px 25px rgba(55,82,43,.07);
}
.cms-kicker:before{
    content:"";
    width:8px;height:8px;border-radius:50%;
    background:var(--stit-green);
}
.cms-hero h1{
    max-width:760px;
    margin:20px 0 18px;
    font-size:clamp(2.7rem,6vw,5.5rem);
    line-height:.96;
    letter-spacing:-.055em;
    font-weight:900;
}
.cms-hero .hero-subtitle{
    max-width:650px;
    color:#52634e;
    font-size:1.18rem;
    line-height:1.7;
}
.cms-actions{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    margin-top:28px;
}
.cms-btn-primary{
    background:var(--stit-green-dark);
    border-color:var(--stit-green-dark);
    color:#fff;
    border-radius:999px;
    padding:12px 20px;
    font-weight:800;
}
.cms-btn-primary:hover{background:#3e6828;border-color:#3e6828;color:#fff}
.cms-btn-soft{
    border:1px solid var(--stit-line);
    background:#fff;
    color:var(--stit-ink);
    border-radius:999px;
    padding:12px 20px;
    font-weight:800;
}
.cms-hero-visual{
    position:absolute;
    right:5%;
    bottom:0;
    width:43%;
    height:82%;
    display:flex;
    align-items:center;
    justify-content:center;
}
.cms-hero-visual:before{
    content:"";
    position:absolute;
    width:390px;height:390px;
    border-radius:50%;
    background:rgba(143,188,85,.18);
}
.cms-hero-visual img{
    position:relative;
    max-height:300px;
    max-width:90%;
    object-fit:contain;
    filter:drop-shadow(0 25px 35px rgba(50,78,40,.14));
}
.cms-floating-card{
    position:absolute;
    left:0;
    bottom:72px;
    background:#fff;
    border:1px solid rgba(77,124,50,.1);
    border-radius:18px;
    padding:14px 16px;
    box-shadow:0 18px 45px rgba(49,75,40,.12);
    z-index:2;
}
.cms-floating-card strong{display:block;font-size:1.15rem}
.cms-floating-card span{font-size:.78rem;color:var(--stit-muted)}
.cms-section{
    padding:92px 20px 0;
}
.cms-section-tight{padding-top:48px}
.cms-section-head{
    display:flex;
    justify-content:space-between;
    align-items:end;
    gap:20px;
    margin-bottom:30px;
}
.cms-eyebrow{
    color:var(--stit-green-dark);
    text-transform:uppercase;
    font-size:.73rem;
    letter-spacing:.12em;
    font-weight:900;
    margin-bottom:8px;
}
.cms-section-title{
    margin:0;
    font-size:clamp(2rem,4vw,3.35rem);
    line-height:1.02;
    letter-spacing:-.045em;
    font-weight:900;
}
.cms-section-copy{
    max-width:620px;
    color:var(--stit-muted);
    line-height:1.75;
}
.cms-stats{
    margin-top:-52px;
    position:relative;
    z-index:3;
}
.cms-stat{
    height:100%;
    background:#fff;
    border:1px solid var(--stit-line);
    border-radius:22px;
    padding:24px;
    box-shadow:0 14px 40px rgba(52,76,43,.07);
}
.cms-stat-number{
    color:var(--stit-green-dark);
    font-size:2.3rem;
    font-weight:900;
    line-height:1;
}
.cms-stat-label{
    color:var(--stit-muted);
    margin-top:8px;
    font-size:.9rem;
}
.cms-about{
    border-radius:30px;
    background:var(--stit-cream);
    padding:42px;
}
.cms-about-image{
    min-height:310px;
    border-radius:24px;
    background:linear-gradient(145deg,#dcebc9,#f7f9ef);
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}
.cms-about-image img{
    max-height:250px;
    max-width:85%;
    object-fit:contain;
}
.cms-card{
    height:100%;
    border:1px solid var(--stit-line);
    border-radius:24px;
    background:#fff;
    box-shadow:none;
    transition:transform .22s ease,box-shadow .22s ease,border-color .22s ease;
}
.cms-card:hover{
    transform:translateY(-5px);
    border-color:#c7dcb6;
    box-shadow:0 18px 42px rgba(52,76,43,.1);
}
.cms-program{
    padding:26px;
    position:relative;
    overflow:hidden;
}
.cms-program-number{
    width:44px;height:44px;
    display:flex;align-items:center;justify-content:center;
    border-radius:14px;
    background:#edf6e3;
    color:var(--stit-green-dark);
    font-weight:900;
    margin-bottom:28px;
}
.cms-program h3{
    font-weight:850;
    letter-spacing:-.025em;
}
.cms-program p{color:var(--stit-muted)}
.cms-arrow{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:38px;height:38px;
    border-radius:50%;
    background:#f2f6ed;
    color:var(--stit-green-dark);
    text-decoration:none;
}
.cms-agenda-list{
    background:#fff;
    border:1px solid var(--stit-line);
    border-radius:26px;
    overflow:hidden;
}
.cms-agenda-item{
    padding:20px 24px;
    border-bottom:1px solid #edf1e9;
}
.cms-agenda-item:last-child{border-bottom:0}
.cms-date{
    color:var(--stit-green-dark);
    font-size:.78rem;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:.04em;
}
.cms-agenda-item h3{font-size:1.05rem;font-weight:800;margin:5px 0 0}
.cms-announcement{
    border-radius:26px;
    background:#20301f;
    color:#fff;
    padding:28px;
}
.cms-announcement a{color:#d9efba}
.cms-news{
    overflow:hidden;
}
.cms-news .card-body{padding:24px}
.cms-news-category{
    color:var(--stit-green-dark);
    font-size:.72rem;
    font-weight:900;
    letter-spacing:.08em;
    text-transform:uppercase;
}
.cms-news h3{
    font-size:1.12rem;
    line-height:1.35;
    font-weight:850;
}
.cms-news-date{font-size:.78rem;color:var(--stit-muted)}
.cms-cta{
    margin:92px 20px 0;
    padding:54px 48px;
    border-radius:32px;
    background:var(--stit-green-dark);
    color:#fff;
    position:relative;
    overflow:hidden;
}
.cms-cta:after{
    content:"";
    position:absolute;
    width:280px;height:280px;
    border:1px solid rgba(255,255,255,.15);
    border-radius:50%;
    right:-70px;top:-110px;
}
.cms-cta h2{
    max-width:720px;
    font-size:clamp(2rem,4vw,3.4rem);
    line-height:1.05;
    letter-spacing:-.04em;
    font-weight:900;
}
.cms-cta p{max-width:650px;color:rgba(255,255,255,.76)}
.cms-cta .btn{
    border-radius:999px;
    padding:12px 20px;
    font-weight:800;
}
.admin-note{
    padding:36px 20px 50px;
    color:#899287;
    font-size:.75rem;
}
@media (max-width:991.98px){
    .cms-hero,.cms-hero-inner{min-height:auto}
    .cms-hero{padding-bottom:40px}
    .cms-hero-visual{
        position:relative;
        right:auto;bottom:auto;
        width:100%;height:330px;
        margin-top:15px;
    }
    .cms-floating-card{left:8%;bottom:18px}
    .cms-stats{margin-top:24px}
    .cms-about{padding:28px}
}
@media (max-width:575.98px){
    .cms-hero-wrap{padding:14px 10px 0}
    .cms-hero{border-radius:26px}
    .cms-hero h1{font-size:2.8rem}
    .cms-section{padding-left:10px;padding-right:10px;padding-top:62px}
    .cms-section-head{display:block}
    .cms-section-head .btn{margin-top:15px}
    .cms-about{padding:20px;border-radius:24px}
    .cms-cta{margin:62px 10px 0;padding:34px 24px}
}
</style>
@endsection

@section('content')
<div class="cms-home">
@php
    $hero = $sections->firstWhere('section_key', 'hero');
    $about = $sections->firstWhere('section_key', 'about');

    $filledSections = $sections->where('section_key', '!=', 'hero')->filter(function ($section) {
        return filled(trim((string) $section->title))
            || filled(trim((string) $section->subtitle))
            || filled(trim((string) $section->content))
            || filled(trim((string) $section->button_url))
            || filled(trim((string) $section->image));
    });
@endphp

    <div class="cms-hero-wrap">
        <section class="cms-hero">
            <div class="container-xl cms-hero-inner">
                <div class="row align-items-center h-100">
                    <div class="col-lg-7 py-5">
                        <span class="cms-kicker">STIT Darul Ilmi Tasikmalaya</span>
                        <h1>{{ $hero?->title ?: $webs->school_name }}</h1>
                        <p class="hero-subtitle">
                            {{ $hero?->subtitle ?: 'Membangun generasi berilmu, berakhlak, dan siap berkontribusi untuk masyarakat.' }}
                        </p>
                        @if($hero?->content)
                            <p class="hero-subtitle fs-6">{!! nl2br(e($hero->content)) !!}</p>
                        @endif
                        <div class="cms-actions">
                            @if($hero?->button_url)
                                <a href="{{ $hero->button_url }}" class="btn cms-btn-primary">{{ $hero->button_text ?: 'Selengkapnya' }} <span class="ms-1">→</span></a>
                            @else
                                <a href="{{ route('root.prodi-index') }}" class="btn cms-btn-primary">Jelajahi Program Studi <span class="ms-1">→</span></a>
                            @endif
                            <a href="{{ route('root.berita-index') }}" class="btn cms-btn-soft">Lihat Berita</a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="cms-hero-visual">
                            <div class="cms-floating-card">
                                <strong>{{ $programStudis->count() }}</strong>
                                <span>Program studi aktif</span>
                            </div>
                            @if($hero?->image)
                                <img src="{{ $hero->image }}" alt="{{ $hero->title ?: $webs->school_name }}">
                            @else
                                <img src="{{ $webs->school_logo_hori }}" alt="Logo {{ $webs->school_name }}">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="cms-section cms-stats">
        <div class="container-xl">
            <div class="row g-3">
                <div class="col-md-4"><div class="cms-stat"><div class="cms-stat-number">{{ $programStudis->count() }}</div><div class="cms-stat-label">Program Studi</div></div></div>
                <div class="col-md-4"><div class="cms-stat"><div class="cms-stat-number">{{ $beritas->count() }}</div><div class="cms-stat-label">Berita & Informasi</div></div></div>
                <div class="col-md-4"><div class="cms-stat"><div class="cms-stat-number">{{ $kalender->count() }}</div><div class="cms-stat-label">Agenda Akademik</div></div></div>
            </div>
        </div>
    </section>

    @if($about && (filled(trim((string)$about->title)) || filled(trim((string)$about->content)) || filled(trim((string)$about->image))))
    <section class="cms-section">
        <div class="container-xl">
            <div class="cms-about">
                <div class="row align-items-center g-4">
                    <div class="col-lg-6">
                        <div class="cms-about-image">
                            @if($about->image)
                                <img src="{{ $about->image }}" alt="{{ $about->title }}">
                            @else
                                <img src="{{ $webs->school_logo_hori }}" alt="Logo {{ $webs->school_name }}">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="cms-eyebrow">Tentang Kami</div>
                        <h2 class="cms-section-title">{{ $about->title ?: 'Pendidikan yang tumbuh bersama nilai dan ilmu' }}</h2>
                        @if($about->subtitle)<p class="fs-5 mt-3 text-secondary">{{ $about->subtitle }}</p>@endif
                        @if($about->content)<div class="cms-section-copy mt-3">{!! nl2br(e($about->content)) !!}</div>@endif
                        @if($about->button_url)<a href="{{ $about->button_url }}" class="btn cms-btn-primary mt-4">{{ $about->button_text ?: 'Pelajari Selengkapnya' }} →</a>@endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @foreach($filledSections as $section)
        @if($section->section_key !== 'about')
        <section class="cms-section cms-section-tight" id="{{ $section->section_key }}">
            <div class="container-xl">
                <div class="row align-items-center g-5">
                    <div class="{{ $section->image ? 'col-lg-7' : 'col-12' }}">
                        <div class="cms-eyebrow">{{ $webs->school_name }}</div>
                        <h2 class="cms-section-title">{{ $section->title ?: ucfirst(str_replace(['-','_'],' ',$section->section_key)) }}</h2>
                        @if($section->subtitle)<p class="fs-5 text-secondary mt-3">{{ $section->subtitle }}</p>@endif
                        @if($section->content)<div class="cms-section-copy mt-3">{!! nl2br(e($section->content)) !!}</div>@endif
                        @if($section->button_url)<a href="{{ $section->button_url }}" class="btn cms-btn-primary mt-4">{{ $section->button_text ?: 'Selengkapnya' }} →</a>@endif
                    </div>
                    @if($section->image)
                    <div class="col-lg-5">
                        <div class="cms-about-image"><img src="{{ $section->image }}" alt="{{ $section->title }}"></div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
        @endif
    @endforeach

    <section class="cms-section">
        <div class="container-xl">
            <div class="cms-section-head">
                <div>
                    <div class="cms-eyebrow">Pendidikan</div>
                    <h2 class="cms-section-title">Program Studi</h2>
                </div>
                <a href="{{ route('root.prodi-index') }}" class="btn cms-btn-soft">Lihat semua →</a>
            </div>
            <div class="row g-3">
                @forelse($programStudis as $index=>$prodi)
                <div class="col-md-6 col-lg-4">
                    <div class="cms-card cms-program">
                        <div class="cms-program-number">{{ str_pad($index+1,2,'0',STR_PAD_LEFT) }}</div>
                        <h3>{{ $prodi->name }}</h3>
                        <p class="mb-4">{{ $prodi->fakultas?->name ?: $prodi->level }}</p>
                        <a href="{{ route('root.prodi-index') }}" class="cms-arrow" aria-label="Lihat program studi">→</a>
                    </div>
                </div>
                @empty
                <div class="col-12"><div class="alert alert-info">Belum ada program studi aktif.</div></div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="cms-section">
        <div class="container-xl">
            <div class="cms-section-head">
                <div>
                    <div class="cms-eyebrow">Kampus</div>
                    <h2 class="cms-section-title">Agenda & Pengumuman</h2>
                </div>
                <a href="{{ route('root.kalender-akademik-index') }}" class="btn cms-btn-soft">Kalender akademik →</a>
            </div>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cms-agenda-list">
                        @forelse($kalender as $item)
                        <div class="cms-agenda-item">
                            <div class="cms-date">{{ \Carbon\Carbon::parse($item->start_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</div>
                            <h3>{{ $item->name }}</h3>
                            @if($item->type)<div class="small text-secondary mt-1">{{ $item->type }}</div>@endif
                        </div>
                        @empty
                        <div class="p-4 text-secondary">Belum ada agenda akademik.</div>
                        @endforelse
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cms-announcement h-100">
                        <div class="cms-eyebrow text-white-50">Informasi</div>
                        <h3 class="h2 fw-bold mb-4">Pengumuman terbaru</h3>
                        @forelse($pengumuman as $item)
                        <div class="mb-4">
                            <a href="{{ route('root.pengumuman-view',$item->slug) }}" class="fw-bold text-decoration-none">{{ $item->name }}</a>
                            <div class="small text-white-50 mt-1">{{ optional($item->created_at)->locale('id')->isoFormat('D MMMM Y') }}</div>
                        </div>
                        @empty
                        <p class="text-white-50 mb-0">Belum ada pengumuman.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cms-section">
        <div class="container-xl">
            <div class="cms-section-head">
                <div>
                    <div class="cms-eyebrow">Kabar Kampus</div>
                    <h2 class="cms-section-title">Berita Terbaru</h2>
                </div>
                <a href="{{ route('root.berita-index') }}" class="btn cms-btn-soft">Semua berita →</a>
            </div>
            <div class="row g-3">
                @forelse($beritas as $item)
                <div class="col-md-6 col-lg-3">
                    <article class="cms-card cms-news">
                        <div class="card-body">
                            <div class="cms-news-category">{{ $item->kategori?->name ?: 'Informasi' }}</div>
                            <h3 class="mt-3 mb-3">{{ $item->title ?? $item->name }}</h3>
                            <div class="cms-news-date mb-4">{{ optional($item->created_at)->locale('id')->isoFormat('D MMMM Y') }}</div>
                            <a href="{{ route('root.berita-view',$item->slug) }}" class="btn cms-btn-primary btn-sm">Baca berita →</a>
                        </div>
                    </article>
                </div>
                @empty
                <div class="col-12"><div class="alert alert-info">Belum ada berita.</div></div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="cms-cta">
        <div class="container-xl position-relative" style="z-index:1">
            <div class="cms-eyebrow text-white-50">STIT Darul Ilmi</div>
            <h2>Temukan ruang untuk belajar, bertumbuh, dan memberi manfaat.</h2>
            <p class="mt-3 mb-4">Jelajahi informasi akademik, program studi, berita, dan agenda kampus melalui halaman depan STIT Darul Ilmi.</p>
            <a href="{{ route('root.prodi-index') }}" class="btn btn-light">Jelajahi Program Studi →</a>
        </div>
    </section>

    <div class="container-xl text-center admin-note">
        Konten section halaman depan dapat dikelola dari Admin Panel → Pengaturan → Front Page.
    </div>
</div>
@endsection
