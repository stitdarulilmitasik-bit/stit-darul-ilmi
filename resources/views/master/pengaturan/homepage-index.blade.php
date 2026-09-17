@extends('core-themes.core-backpage')

@section('custom-css')
<style>
    .section-card { border: 1px solid var(--tblr-border-color); border-radius: 12px; margin-bottom: 1rem; }
    .section-card .card-header { background: var(--tblr-bg-surface-secondary); }
    .drag-handle { cursor: move; color: var(--tblr-secondary); }
    .preview-box { border: 1px dashed var(--tblr-border-color); border-radius: 10px; padding: 1rem; background: var(--tblr-bg-surface-secondary); }
</style>
@endsection

@section('content')
<div class="container-xl">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Front Page Website</h2>
                <div class="text-secondary">Kelola teks, section, tombol, gambar, dan urutan konten halaman depan.</div>
            </div>
            <div class="col-auto">
                <a href="{{ route('root.home-index') }}" target="_blank" class="btn btn-outline-primary">Lihat Website</a>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h3 class="card-title">Tambah Section</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route($spref . 'pengaturan.front-page-store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">Key Section</label><input name="section_key" class="form-control" placeholder="hero" required></div>
                    <div class="col-md-3"><label class="form-label">Judul</label><input name="title" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label">Subjudul</label><input name="subtitle" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label">Urutan</label><input type="number" name="sort_order" value="0" min="0" class="form-control"></div>
                    <div class="col-12"><label class="form-label">Isi Konten</label><textarea name="content" rows="4" class="form-control" placeholder="Isi teks section..."></textarea></div>
                    <div class="col-md-4"><label class="form-label">URL Gambar</label><input name="image" class="form-control" placeholder="images/... atau URL"></div>
                    <div class="col-md-4"><label class="form-label">Teks Tombol</label><input name="button_text" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label">URL Tombol</label><input name="button_url" class="form-control"></div>
                    <div class="col-12"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" checked><span class="form-check-label">Tampilkan di front page</span></label></div>
                    <div class="col-12"><button class="btn btn-primary">Simpan Section</button></div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($sections as $section)
            <div class="col-12">
                <div class="card section-card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto"><span class="drag-handle">☷</span></div>
                            <div class="col"><strong>{{ $section->title ?: $section->section_key }}</strong><div class="text-secondary small">{{ $section->section_key }}</div></div>
                            <div class="col-auto"><span class="badge {{ $section->is_active ? 'bg-success-lt text-success' : 'bg-secondary-lt' }}">{{ $section->is_active ? 'Aktif' : 'Nonaktif' }}</span></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route($spref . 'pengaturan.front-page-update', $section) }}">
                            @csrf @method('PATCH')
                            <div class="row g-3">
                                <div class="col-md-3"><label class="form-label">Key</label><input name="section_key" class="form-control" value="{{ $section->section_key }}" required></div>
                                <div class="col-md-3"><label class="form-label">Judul</label><input name="title" class="form-control" value="{{ $section->title }}"></div>
                                <div class="col-md-3"><label class="form-label">Subjudul</label><input name="subtitle" class="form-control" value="{{ $section->subtitle }}"></div>
                                <div class="col-md-3"><label class="form-label">Urutan</label><input type="number" name="sort_order" min="0" class="form-control" value="{{ $section->sort_order }}"></div>
                                <div class="col-12"><label class="form-label">Isi Konten</label><textarea name="content" rows="4" class="form-control">{{ $section->content }}</textarea></div>
                                <div class="col-md-4"><label class="form-label">URL Gambar</label><input name="image" class="form-control" value="{{ $section->image }}"></div>
                                <div class="col-md-4"><label class="form-label">Teks Tombol</label><input name="button_text" class="form-control" value="{{ $section->button_text }}"></div>
                                <div class="col-md-4"><label class="form-label">URL Tombol</label><input name="button_url" class="form-control" value="{{ $section->button_url }}"></div>
                                <div class="col-12"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $section->is_active ? 'checked' : '' }}><span class="form-check-label">Tampilkan di front page</span></label></div>
                                <div class="col-12 d-flex gap-2"><button class="btn btn-primary">Simpan Perubahan</button></div>
                            </div>
                        </form>
                        <form method="POST" action="{{ route($spref . 'pengaturan.front-page-delete', $section) }}" class="mt-2" onsubmit="return confirm('Hapus section ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">Hapus Section</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="empty"><p class="empty-title">Belum ada section</p><p class="empty-subtitle text-secondary">Tambahkan section pertama untuk mulai mengelola front page dari admin.</p></div></div>
        @endforelse
    </div>
</div>
@endsection
