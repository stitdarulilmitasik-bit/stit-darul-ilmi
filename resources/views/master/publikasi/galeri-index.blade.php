@extends('core-themes.core-backpage')

@section('custom-css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .card { border: none; box-shadow: 0 0 12px rgba(0,0,0,.06); border-radius: 12px; }
    .card-header { background: transparent; border-bottom: 1px solid rgba(0,0,0,.06); }
    .form-control, .form-select { border-radius: 7px; }
    .upload-box { border: 2px dashed #d9dee7; border-radius: 10px; padding: 18px; background: #fafbfc; }
    .preview-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(110px,1fr)); gap:10px; margin-top:12px; }
    .preview-grid img { width:100%; height:100px; object-fit:cover; border-radius:8px; border:1px solid #e5e7eb; }
    .image-preview { max-width:220px; max-height:160px; margin-top:10px; border-radius:8px; object-fit:cover; }
    .badge { padding:.5em .75em; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 col-12 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $pages }}</h5>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Galeri
                </button>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 mb-2"><div class="p-3 bg-light-primary rounded"><h6>Total Galeri</h6><h3 class="mb-0">{{ $galeri->count() }}</h3></div></div>
                    <div class="col-md-4 mb-2"><div class="p-3 bg-light-success rounded"><h6>Galeri Publish</h6><h3 class="mb-0">{{ $galeri->where('status','Publish')->count() }}</h3></div></div>
                    <div class="col-md-4 mb-2"><div class="p-3 bg-light-warning rounded"><h6>Galeri Draft</h6><h3 class="mb-0">{{ $galeri->where('status','Draft')->count() }}</h3></div></div>
                </div>

                <div class="collapse" id="collapseForm">
                    <div class="card card-body border mb-4">
                        <h5 class="mb-1">Tambah Galeri Baru</h5>
                        <p class="text-muted small mb-4">Satu foto digunakan sebagai sampul. Foto dokumentasi dapat dipilih sekaligus dalam jumlah banyak.</p>
                        <form action="{{ route($spref . 'publikasi.galeri-handle') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kategori</label>
                                <select class="form-select" name="kategori_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Judul Galeri</label>
                                <input type="text" class="form-control" name="name" required>
                                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3 upload-box">
                                <label class="form-label fw-semibold"><i class="fas fa-image me-1"></i> Foto Sampul</label>
                                <input type="file" class="form-control" name="photo" id="cover_photo" accept="image/jpeg,image/png,image/jpg,image/webp" required onchange="previewCover(this)">
                                <div class="small text-muted mt-2">Foto utama yang tampil sebagai thumbnail/cover galeri. Maksimal 4 MB.</div>
                                <img id="cover_preview" class="image-preview d-none">
                                @error('photo')<small class="text-danger d-block">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3 upload-box">
                                <label class="form-label fw-semibold"><i class="fas fa-images me-1"></i> Dokumentasi Foto</label>
                                <input type="file" class="form-control" name="photos[]" id="gallery_photos" accept="image/jpeg,image/png,image/jpg,image/webp" multiple onchange="previewMultipleImages(this)">
                                <div class="small text-muted mt-2"><strong>Bisa pilih banyak foto sekaligus.</strong> Gunakan Ctrl/Shift saat memilih file. Maksimal 4 MB per foto.</div>
                                <div id="photos_preview" class="preview-grid"></div>
                                @error('photos')<small class="text-danger d-block">{{ $message }}</small>@enderror
                                @error('photos.*')<small class="text-danger d-block">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi Dokumentasi</label>
                                <textarea class="form-control" name="desc" rows="3" placeholder="Deskripsi yang akan digunakan untuk foto-foto dokumentasi (opsional)"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Konten Galeri</label>
                                <textarea class="form-control" name="content" rows="5" required></textarea>
                                @error('content')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="Draft">Draft</option>
                                    <option value="Publish">Publish</option>
                                    <option value="Archive">Archive</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Galeri</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr><th class="text-center">No</th><th>Judul</th><th>Kategori</th><th>Status</th><th class="text-center">Aksi</th></tr>
                        </thead>
                        <tbody>
                        @forelse($galeri as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ optional($item->kategori)->name }}</td>
                                <td><span class="badge bg-{{ $item->status == 'Publish' ? 'success' : ($item->status == 'Draft' ? 'warning' : 'secondary') }}">{{ $item->status }}</span></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route($spref.'publikasi.galeri-view', $item->code) }}" class="btn btn-sm btn-secondary" title="Kelola foto"><i class="fas fa-images"></i></a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#editData{{ $item->code }}" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route($spref . 'publikasi.galeri-delete', $item->code) }}" method="POST" id="delete-form-{{ $item->code }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $item->code }}')" title="Hapus"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data galeri.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-12 mb-3">
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Petunjuk Galeri</h5></div>
            <div class="card-body">
                <ol class="mb-0 ps-3">
                    <li class="mb-2">Pilih kategori dan isi judul kegiatan.</li>
                    <li class="mb-2">Pilih <strong>1 Foto Sampul</strong>.</li>
                    <li class="mb-2">Pada <strong>Dokumentasi Foto</strong>, pilih banyak foto sekaligus.</li>
                    <li class="mb-2">Simpan. Semua dokumentasi akan masuk ke galeri yang sama.</li>
                    <li>Ikon <i class="fas fa-images"></i> digunakan untuk menambah/mengelola foto setelah galeri dibuat.</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@foreach ($galeri as $item)
<div class="modal fade" id="editData{{ $item->code }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route($spref . 'publikasi.galeri-update', $item->code) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PATCH')
                <div class="modal-header"><h5 class="modal-title">Edit Galeri</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Kategori</label><select class="form-select" name="kategori_id" required><option value="">Pilih Kategori</option>@foreach($kategori as $kat)<option value="{{ $kat->id }}" {{ $item->kategori_id == $kat->id ? 'selected' : '' }}>{{ $kat->name }}</option>@endforeach</select></div>
                    <div class="mb-3"><label class="form-label">Judul Galeri</label><input type="text" class="form-control" name="name" value="{{ $item->name }}" required></div>
                    <div class="mb-3"><label class="form-label">Ganti Foto Sampul</label><input type="file" class="form-control" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp"><small class="text-muted">Kosongkan jika tidak ingin mengganti sampul.</small></div>
                    <div class="mb-3"><label class="form-label">Konten Galeri</label><textarea class="form-control" name="content" rows="5" required>{{ $item->content }}</textarea></div>
                    <div class="mb-3"><label class="form-label">Status</label><select class="form-select" name="status" required><option value="Draft" {{ $item->status == 'Draft' ? 'selected' : '' }}>Draft</option><option value="Publish" {{ $item->status == 'Publish' ? 'selected' : '' }}>Publish</option><option value="Archive" {{ $item->status == 'Archive' ? 'selected' : '' }}>Archive</option></select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Simpan Perubahan</button></div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
function previewCover(input) {
    const preview = document.getElementById('cover_preview');
    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('d-none');
    } else {
        preview.src = '';
        preview.classList.add('d-none');
    }
}

function previewMultipleImages(input) {
    const container = document.getElementById('photos_preview');
    container.innerHTML = '';
    if (!input.files) return;
    Array.from(input.files).forEach(file => {
        if (!file.type.startsWith('image/')) return;
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.title = file.name;
        container.appendChild(img);
    });
}

function confirmDelete(code) {
    const form = document.getElementById('delete-form-' + code);
    if (!form) return;
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Hapus galeri?',
            text: 'Galeri beserta seluruh dokumentasi fotonya akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => { if (result.isConfirmed) form.submit(); });
    } else if (confirm('Hapus galeri beserta seluruh dokumentasi fotonya?')) {
        form.submit();
    }
}
</script>
@endpush
