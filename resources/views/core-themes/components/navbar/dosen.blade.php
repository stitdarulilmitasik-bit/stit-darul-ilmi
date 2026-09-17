<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#navbar-dosen-akademik" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M22 9l-10 -4l-10 4l10 4l10 -4"/><path d="M6 10.6v5.4a6 6 0 0 0 12 0v-5.4"/></svg>
        </span>
        <span class="nav-link-title">Akademik</span>
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{ route('dosen.akademik.jadwal') }}">Jadwal Kuliah</a>
        <a class="dropdown-item" href="{{ route('dosen.akademik.krs') }}">Persetujuan KRS</a>
        <a class="dropdown-item" href="{{ route('dosen.akademik.nilai') }}">Input / Update Nilai</a>
        <a class="dropdown-item" href="{{ route('dosen.akademik.master') }}">Master Akademik</a>
    </div>
</li>
