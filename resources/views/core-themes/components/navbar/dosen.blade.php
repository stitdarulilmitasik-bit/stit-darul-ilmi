<li class="nav-item">
    <a class="nav-link {{ Route::is('dosen.dashboard-render', request()->path()) ? 'active' : '' }}" href="{{ route('dosen.dashboard-render') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l-2 -0l9 -9l9 9l-2 0"/><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"/><path d="M9 21v-6a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6"/></svg>
        </span>
        <span class="nav-link-title">Dashboard</span>
    </a>
</li>

<li class="nav-item">
    <span class="nav-link"><span class="nav-link-title">Data Akademik</span></span>
</li>

<li class="nav-item dropdown">
    <a class="nav-link {{ Route::is('dosen.akademik.*') ? 'active' : '' }} dropdown-toggle" href="#navbar-dosen-akademik" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M22 9l-10 -4l-10 4l10 -4"/><path d="M6 10.6v5.4a6 6 0 0 0 12 0v-5.4"/></svg>
        </span>
        <span class="nav-link-title">Akademik</span>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item {{ Route::is('dosen.akademik.master') ? 'active' : '' }}" href="{{ route('dosen.akademik.master') }}">Mata Kuliah yang Saya Ampu</a>
        <a class="dropdown-item {{ Route::is('dosen.akademik.jadwal') ? 'active' : '' }}" href="{{ route('dosen.akademik.jadwal') }}">Jadwal Kuliah</a>
        <a class="dropdown-item {{ Route::is('dosen.akademik.krs') ? 'active' : '' }}" href="{{ route('dosen.akademik.krs') }}">Persetujuan KRS</a>
        <a class="dropdown-item {{ Route::is('dosen.akademik.nilai') ? 'active' : '' }}" href="{{ route('dosen.akademik.nilai') }}">Input / Update Nilai</a>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link {{ Route::is('dosen.absensi-render', request()->path()) ? 'active' : '' }}" href="{{ route('dosen.absensi-render') }}">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M8 7a4 4 0 1 0 8 0"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4"/><path d="M15 19l2 2l4 -4"/></svg>
        </span>
        <span class="nav-link-title">Absensi</span>
    </a>
</li>
