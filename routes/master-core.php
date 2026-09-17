<?php

use Illuminate\Support\Facades\Route;

    // MASTER PENGATURAN => FRONT PAGE CMS
    Route::get('/pengaturan/front-page', [App\Http\Controllers\Master\Pengaturan\HomepageController::class, 'index'])->name('pengaturan.front-page-render');
    Route::post('/pengaturan/front-page', [App\Http\Controllers\Master\Pengaturan\HomepageController::class, 'store'])->name('pengaturan.front-page-store');
    Route::patch('/pengaturan/front-page/{section}', [App\Http\Controllers\Master\Pengaturan\HomepageController::class, 'update'])->name('pengaturan.front-page-update');
    Route::delete('/pengaturan/front-page/{section}', [App\Http\Controllers\Master\Pengaturan\HomepageController::class, 'destroy'])->name('pengaturan.front-page-delete');

    // MASTER AKADEMIK => TAHUN AKADEMIK
    Route::get('/akademik/tahun-akademik',[App\Http\Controllers\Master\Akademik\TahunAkademikController::class, 'renderTaka'])->name('akademik.taka-render');
    Route::post('/akademik/tahun-akademik',[App\Http\Controllers\Master\Akademik\TahunAkademikController::class, 'handleTaka'])->name('akademik.taka-handle');
    Route::patch('/akademik/tahun-akademik/{code}',[App\Http\Controllers\Master\Akademik\TahunAkademikController::class, 'updateTaka'])->name('akademik.taka-update');
    Route::delete('/akademik/tahun-akademik/{code}',[App\Http\Controllers\Master\Akademik\TahunAkademikController::class, 'deleteTaka'])->name('akademik.taka-delete');

    // MASTER AKADEMIK => PROGRAM STUDI
    Route::get('/akademik/program-studi',[App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'renderProdi'])->name('akademik.prodi-render');
    Route::post('/akademik/program-studi',[App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'handleProdi'])->name('akademik.prodi-handle');
    Route::patch('/akademik/program-studi/{code}',[App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'updateProdi'])->name('akademik.prodi-update');
    Route::delete('/akademik/program-studi/{code}',[App\Http\Controllers\Master\Akademik\ProgramStudiController::class, 'deleteProdi'])->name('akademik.prodi-delete');

    // MASTER AKADEMIK => FAKULTAS
    Route::get('/akademik/fakultas',[App\Http\Controllers\Master\Akademik\FakultasController::class, 'renderFakultas'])->name('akademik.fakultas-render');
    Route::post('/akademik/fakultas',[App\Http\Controllers\Master\Akademik\FakultasController::class, 'handleFakultas'])->name('akademik.fakultas-handle');
    Route::patch('/akademik/fakultas/{code}',[App\Http\Controllers\Master\Akademik\FakultasController::class, 'updateFakultas'])->name('akademik.fakultas-update');
    Route::delete('/akademik/fakultas/{code}',[App\Http\Controllers\Master\Akademik\FakultasController::class, 'deleteFakultas'])->name('akademik.fakultas-delete');

    // MASTER AKADEMIK => KURIKULUM
    Route::get('/akademik/kurikulum',[App\Http\Controllers\Master\Akademik\KurikulumController::class, 'renderKurikulum'])->name('akademik.kurikulum-render');
    Route::post('/akademik/kurikulum',[App\Http\Controllers\Master\Akademik\KurikulumController::class, 'handleKurikulum'])->name('akademik.kurikulum-handle');
    Route::patch('/akademik/kurikulum/{code}',[App\Http\Controllers\Master\Akademik\KurikulumController::class, 'updateKurikulum'])->name('akademik.kurikulum-update');
    Route::delete('/akademik/kurikulum/{code}',[App\Http\Controllers\Master\Akademik\KurikulumController::class, 'deleteKurikulum'])->name('akademik.kurikulum-delete');

    // MASTER AKADEMIK => MATAKULIAH
    Route::get('/akademik/mata-kuliah',[App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'renderMataKuliah'])->name('akademik.mata-kuliah-render');
    Route::post('/akademik/mata-kuliah',[App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'handleMataKuliah'])->name('akademik.mata-kuliah-handle');
    Route::patch('/akademik/mata-kuliah/{code}',[App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'updateMataKuliah'])->name('akademik.mata-kuliah-update');
    Route::delete('/akademik/mata-kuliah/{code}',[App\Http\Controllers\Master\Akademik\MataKuliahController::class, 'deleteMataKuliah'])->name('akademik.mata-kuliah-delete');

    // MASTER AKADEMIK => KELAS
    Route::get('/akademik/kelas',[App\Http\Controllers\Master\Akademik\KelasController::class, 'renderKelas'])->name('akademik.kelas-render');
    Route::post('/akademik/kelas',[App\Http\Controllers\Master\Akademik\KelasController::class, 'handleKelas'])->name('akademik.kelas-handle');
    Route::patch('/akademik/kelas/{code}',[App\Http\Controllers\Master\Akademik\KelasController::class, 'updateKelas'])->name('akademik.kelas-update');
    Route::delete('/akademik/kelas/{code}',[App\Http\Controllers\Master\Akademik\KelasController::class, 'deleteKelas'])->name('akademik.kelas-delete');

    // MASTER AKADEMIK => JADWAL KULIAH
    Route::get('/akademik/jadwal-kuliah',[App\Http\Controllers\Master\Akademik\JadwalKuliahController::class, 'renderJadwalKuliah'])->name('akademik.jadwal-kuliah-render');
    Route::post('/akademik/jadwal-kuliah',[App\Http\Controllers\Master\Akademik\JadwalKuliahController::class, 'handleJadwalKuliah'])->name('akademik.jadwal-kuliah-handle');
    Route::patch('/akademik/jadwal-kuliah/{code}',[App\Http\Controllers\Master\Akademik\JadwalKuliahController::class, 'updateJadwalKuliah'])->name('akademik.jadwal-kuliah-update');
    Route::delete('/akademik/jadwal-kuliah/{code}',[App\Http\Controllers\Master\Akademik\JadwalKuliahController::class, 'deleteJadwalKuliah'])->name('akademik.jadwal-kuliah-delete');
    Route::get('akademik/get-waktu-kuliah/{jenis_kelas_id}', [App\Http\Controllers\Master\Akademik\JadwalKuliahController::class, 'getWaktuKuliahByJenisKelas'])->name('akademik.get-waktu-kuliah');

    // MASTER AKADEMIK => KRS
    Route::get('/akademik/krs',[App\Http\Controllers\Master\Akademik\KRSController::class, 'renderKRS'])->name('akademik.krs-render');
    Route::post('/akademik/krs',[App\Http\Controllers\Master\Akademik\KRSController::class, 'handleKRS'])->name('akademik.krs-handle');
    Route::get('/akademik/krs/{code}/detail',[App\Http\Controllers\Master\Akademik\KRSController::class, 'detailKRS'])->name('akademik.krs-detail');
    Route::get('/akademik/krs/{code}/print',[App\Http\Controllers\Master\Akademik\KRSController::class, 'printKRS'])->name('akademik.krs-print');
    Route::get('/akademik/krs/{code}',[App\Http\Controllers\Master\Akademik\KRSController::class, 'viewKRS'])->name('akademik.krs-view');
    Route::patch('/akademik/krs/{code}',[App\Http\Controllers\Master\Akademik\KRSController::class, 'updateKRS'])->name('akademik.krs-update');
    Route::delete('/akademik/krs/{code}',[App\Http\Controllers\Master\Akademik\KRSController::class, 'deleteKRS'])->name('akademik.krs-delete');
    Route::post('/akademik/krs/{code}/add-matakuliah',[App\Http\Controllers\Master\Akademik\KRSController::class, 'addMatakuliah'])->name('akademik.krs-add-matakuliah');
    Route::delete('/akademik/krs/{code}/remove-matakuliah/{detailId}',[App\Http\Controllers\Master\Akademik\KRSController::class, 'removeMatakuliah'])->name('akademik.krs-remove-matakuliah');
    Route::post('/akademik/krs/{code}/approve',[App\Http\Controllers\Master\Akademik\KRSController::class, 'approveKRS'])->name('akademik.krs-approve');
    Route::post('/akademik/krs/{code}/reject',[App\Http\Controllers\Master\Akademik\KRSController::class, 'rejectKRS'])->name('akademik.krs-reject');
    Route::post('/akademik/krs/{code}/publish',[App\Http\Controllers\Master\Akademik\KRSController::class, 'publishKRS'])->name('akademik.krs-publish');
    Route::post('/akademik/krs/{code}/lock',[App\Http\Controllers\Master\Akademik\KRSController::class, 'lockKRS'])->name('akademik.krs-lock');
    Route::post('/akademik/krs/bulk-approve',[App\Http\Controllers\Master\Akademik\KRSController::class, 'bulkApprove'])->name('akademik.krs-bulk-approve');
    Route::post('/akademik/krs/bulk-publish',[App\Http\Controllers\Master\Akademik\KRSController::class, 'bulkPublish'])->name('akademik.krs-bulk-publish');

    // MASTER AKADEMIK => NILAI
    Route::get('/akademik/nilai',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'renderNilai'])->name('akademik.nilai-render');
    Route::get('/akademik/nilai/import',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'renderImportNilai'])->name('akademik.nilai-import');
    Route::get('/akademik/nilai/export',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'exportNilai'])->name('akademik.nilai-export');
    Route::post('/akademik/nilai',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'handleNilai'])->name('akademik.nilai-handle');
    Route::get('/akademik/nilai/{code}',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'viewNilai'])->name('akademik.nilai-view');
    Route::patch('/akademik/nilai/{code}',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'updateNilai'])->name('akademik.nilai-update');
    Route::delete('/akademik/nilai/{code}',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'deleteNilai'])->name('akademik.nilai-delete');
    Route::post('/akademik/nilai/import',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'importNilai'])->name('akademik.nilai-import-process');
    Route::post('/akademik/nilai/bulk-update',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'bulkUpdate'])->name('akademik.nilai-bulk-update');
    Route::post('/akademik/nilai/{code}/approve',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'approveNilai'])->name('akademik.nilai-approve');
    Route::post('/akademik/nilai/{code}/publish',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'publishNilai'])->name('akademik.nilai-publish');
    Route::post('/akademik/nilai/{code}/lock',[App\Http\Controllers\Master\Akademik\NilaiController::class, 'lockNilai'])->name('akademik.nilai-lock');

    // MASTER AKADEMIK => KHS
    Route::get('/akademik/khs',[App\Http\Controllers\Master\Akademik\KHSController::class, 'renderKHS'])->name('akademik.khs-render');
    Route::post('/akademik/khs',[App\Http\Controllers\Master\Akademik\KHSController::class, 'handleKHS'])->name('akademik.khs-handle');
    Route::get('/akademik/khs/{code}/detail',[App\Http\Controllers\Master\Akademik\KHSController::class, 'detailKHS'])->name('akademik.khs-detail');
    Route::get('/akademik/khs/{code}/print',[App\Http\Controllers\Master\Akademik\KHSController::class, 'printKHS'])->name('akademik.khs-print');
    Route::get('/akademik/khs/{code}',[App\Http\Controllers\Master\Akademik\KHSController::class, 'viewKHS'])->name('akademik.khs-view');
    Route::patch('/akademik/khs/{code}',[App\Http\Controllers\Master\Akademik\KHSController::class, 'updateKHS'])->name('akademik.khs-update');
    Route::delete('/akademik/khs/{code}',[App\Http\Controllers\Master\Akademik\KHSController::class, 'deleteKHS'])->name('akademik.khs-delete');
    Route::post('/akademik/khs/generate',[App\Http\Controllers\Master\Akademik\KHSController::class, 'generateKHS'])->name('akademik.khs-generate');
    Route::post('/akademik/khs/{code}/publish',[App\Http\Controllers\Master\Akademik\KHSController::class, 'publishKHS'])->name('akademik.khs-publish');
    Route::post('/akademik/khs/{code}/lock',[App\Http\Controllers\Master\Akademik\KHSController::class, 'lockKHS'])->name('akademik.khs-lock');
    Route::get('/akademik/khs/transkrip/{mahasiswa_code}',[App\Http\Controllers\Master\Akademik\KHSController::class, 'transkrip'])->name('akademik.khs-transkrip');
    Route::post('/akademik/khs/bulk-generate',[App\Http\Controllers\Master\Akademik\KHSController::class, 'bulkGenerate'])->name('akademik.khs-bulk-generate');
    Route::post('/akademik/khs/bulk-publish',[App\Http\Controllers\Master\Akademik\KHSController::class, 'bulkPublish'])->name('akademik.khs-bulk-publish');

    // MASTER AKADEMIK => JENIS KELAS
    Route::get('/akademik/jenis-kelas',[App\Http\Controllers\Master\Akademik\JenisKelasController::class, 'renderJenisKelas'])->name('akademik.jenis-kelas-render');
    Route::post('/akademik/jenis-kelas',[App\Http\Controllers\Master\Akademik\JenisKelasController::class, 'handleJenisKelas'])->name('akademik.jenis-kelas-handle');
    Route::patch('/akademik/jenis-kelas/{code}',[App\Http\Controllers\Master\Akademik\JenisKelasController::class, 'updateJenisKelas'])->name('akademik.jenis-kelas-update');
    Route::delete('/akademik/jenis-kelas/{code}',[App\Http\Controllers\Master\Akademik\JenisKelasController::class, 'deleteJenisKelas'])->name('akademik.jenis-kelas-delete');

    // MASTER AKADEMIK => WAKTU KULIAH
    Route::get('/akademik/waktu-kuliah',[App\Http\Controllers\Master\Akademik\WaktuKuliahController::class, 'renderWaktuKuliah'])->name('akademik.waktu-kuliah-render');
    Route::post('/akademik/waktu-kuliah',[App\Http\Controllers\Master\Akademik\WaktuKuliahController::class, 'handleWaktuKuliah'])->name('akademik.waktu-kuliah-handle');
    Route::patch('/akademik/waktu-kuliah/{code}',[App\Http\Controllers\Master\Akademik\WaktuKuliahController::class, 'updateWaktuKuliah'])->name('akademik.waktu-kuliah-update');
    Route::delete('/akademik/waktu-kuliah/{code}',[App\Http\Controllers\Master\Akademik\WaktuKuliahController::class, 'deleteWaktuKuliah'])->name('akademik.waktu-kuliah-delete');

    // MASTER PUBLIKASI => KALENDER AKADEMIK
    Route::get('/publikasi/kalender-akademik', [App\Http\Controllers\Master\Publikasi\KalenderAkademikController::class, 'renderKalenderAkademik'])->name('publikasi.kalender-akademik-render');
    Route::get('/publikasi/kalender-akademik/{code}/view', [App\Http\Controllers\Master\Publikasi\KalenderAkademikController::class, 'viewKalenderAkademik'])->name('publikasi.kalender-akademik-view');
    Route::post('/publikasi/kalender-akademik', [App\Http\Controllers\Master\Publikasi\KalenderAkademikController::class, 'handleKalenderAkademik'])->name('publikasi.kalender-akademik-handle');
    Route::patch('/publikasi/kalender-akademik/{code}', [App\Http\Controllers\Master\Publikasi\KalenderAkademikController::class, 'updateKalenderAkademik'])->name('publikasi.kalender-akademik-update');
    Route::delete('/publikasi/kalender-akademik/{code}', [App\Http\Controllers\Master\Publikasi\KalenderAkademikController::class, 'deleteKalenderAkademik'])->name('publikasi.kalender-akademik-delete');

    // MASTER PUBLIKASI => KATEGORI
    Route::get('/publikasi/kategori', [App\Http\Controllers\Master\Publikasi\KategoriController::class, 'renderKategori'])->name('publikasi.kategori-render');
    Route::post('/publikasi/kategori', [App\Http\Controllers\Master\Publikasi\KategoriController::class, 'handleKategori'])->name('publikasi.kategori-handle');
    Route::patch('/publikasi/kategori/{code}', [App\Http\Controllers\Master\Publikasi\KategoriController::class, 'updateKategori'])->name('publikasi.kategori-update');
    Route::delete('/publikasi/kategori/{code}', [App\Http\Controllers\Master\Publikasi\KategoriController::class, 'deleteKategori'])->name('publikasi.kategori-delete');

    // MASTER PUBLIKASI => BERITA
    Route::get('/publikasi/berita', [App\Http\Controllers\Master\Publikasi\BeritaController::class, 'renderBerita'])->name('publikasi.berita-render');
    Route::get('/publikasi/berita/{code}/view', [App\Http\Controllers\Master\Publikasi\BeritaController::class, 'viewBerita'])->name('publikasi.berita-view');
    Route::post('/publikasi/berita', [App\Http\Controllers\Master\Publikasi\BeritaController::class, 'handleBerita'])->name('publikasi.berita-handle');
    Route::patch('/publikasi/berita/{code}', [App\Http\Controllers\Master\Publikasi\BeritaController::class, 'updateBerita'])->name('publikasi.berita-update');
    Route::delete('/publikasi/berita/{code}', [App\Http\Controllers\Master\Publikasi\BeritaController::class, 'deleteBerita'])->name('publikasi.berita-delete');

    // MASTER PUBLIKASI => PENGUMUMAN
    Route::get('/publikasi/pengumuman', [App\Http\Controllers\Master\Publikasi\PengumumanController::class, 'renderPengumuman'])->name('publikasi.pengumuman-render');
    Route::get('/publikasi/pengumuman/{code}/view', [App\Http\Controllers\Master\Publikasi\PengumumanController::class, 'viewPengumuman'])->name('publikasi.pengumuman-view');
    Route::post('/publikasi/pengumuman', [App\Http\Controllers\Master\Publikasi\PengumumanController::class, 'handlePengumuman'])->name('publikasi.pengumuman-handle');
    Route::patch('/publikasi/pengumuman/{code}', [App\Http\Controllers\Master\Publikasi\PengumumanController::class, 'updatePengumuman'])->name('publikasi.pengumuman-update');
    Route::delete('/publikasi/pengumuman/{code}', [App\Http\Controllers\Master\Publikasi\PengumumanController::class, 'deletePengumuman'])->name('publikasi.pengumuman-delete');

    // MASTER PUBLIKASI => GALERI
    Route::get('/publikasi/galeri', [App\Http\Controllers\Master\Publikasi\GaleriController::class, 'renderGaleri'])->name('publikasi.galeri-render');
    Route::get('/publikasi/galeri/{code}/view', [App\Http\Controllers\Master\Publikasi\GaleriController::class, 'viewGaleri'])->name('publikasi.galeri-view');
    Route::post('/publikasi/galeri', [App\Http\Controllers\Master\Publikasi\GaleriController::class, 'handleGaleri'])->name('publikasi.galeri-handle');
    Route::patch('/publikasi/galeri/{code}', [App\Http\Controllers\Master\Publikasi\GaleriController::class, 'updateGaleri'])->name('publikasi.galeri-update');
    Route::delete('/publikasi/galeri/{code}', [App\Http\Controllers\Master\Publikasi\GaleriController::class, 'deleteGaleri'])->name('publikasi.galeri-delete');
    Route::post('/publikasi/galeri/{code}/foto', [App\Http\Controllers\Master\Publikasi\GaleriController::class, 'handleFoto'])->name('publikasi.galeri-foto-handle');
    Route::delete('/publikasi/galeri/foto/{code}', [App\Http\Controllers\Master\Publikasi\GaleriController::class, 'deleteFoto'])->name('publikasi.galeri-foto-delete');

    // MASTER PENGATURAN => WEB SETTINGS
    Route::get('/pengaturan/web-settings', [App\Http\Controllers\Master\Pengaturan\WebSettingController::class, 'renderIndex'])->name('pengaturan.web-settings-render');
    Route::patch('/pengaturan/web-settings', [App\Http\Controllers\Master\Pengaturan\WebSettingController::class, 'handleSettings'])->name('pengaturan.web-settings-handle');
    Route::get('/pengaturan/export-settings', [App\Http\Controllers\Master\Pengaturan\WebSettingController::class, 'exportDatabase'])->name('pengaturan.export-database');
    Route::post('/pengaturan/import-settings', [App\Http\Controllers\Master\Pengaturan\WebSettingController::class, 'importDatabase'])->name('pengaturan.import-database');

    // MASTER PENGATURAN => LOG AKTIVITAS
    Route::get('/pengaturan/log-aktivitas', [App\Http\Controllers\Master\Pengaturan\LogAktivitasController::class, 'renderLogAktivitas'])->name('pengaturan.log-aktivitas-render');
    Route::get('/pengaturan/log-aktivitas/{id}/view', [App\Http\Controllers\Master\Pengaturan\LogAktivitasController::class, 'viewLogAktivitas'])->name('pengaturan.log-aktivitas-view');
    Route::get('/pengaturan/log-aktivitas/filter', [App\Http\Controllers\Master\Pengaturan\LogAktivitasController::class, 'filterLogAktivitas'])->name('pengaturan.log-aktivitas-filter');
    Route::delete('/pengaturan/log-aktivitas/{id}', [App\Http\Controllers\Master\Pengaturan\LogAktivitasController::class, 'deleteLogAktivitas'])->name('pengaturan.log-aktivitas-delete');

    // MASTER INFRASTRUKTUR => GEDUNG
    Route::get('/infrastruktur/gedung', [App\Http\Controllers\Master\Infrastruktur\GedungController::class, 'renderGedung'])->name('infrastruktur.gedung-render');
    Route::post('/infrastruktur/gedung', [App\Http\Controllers\Master\Infrastruktur\GedungController::class, 'handleGedung'])->name('infrastruktur.gedung-handle');
    Route::patch('/infrastruktur/gedung/{code}', [App\Http\Controllers\Master\Infrastruktur\GedungController::class, 'updateGedung'])->name('infrastruktur.gedung-update');
    Route::delete('/infrastruktur/gedung/{code}', [App\Http\Controllers\Master\Infrastruktur\GedungController::class, 'deleteGedung'])->name('infrastruktur.gedung-delete');

    // MASTER INFRASTRUKTUR => RUANG
    Route::get('/infrastruktur/ruang', [App\Http\Controllers\Master\Infrastruktur\RuangController::class, 'renderRuang'])->name('infrastruktur.ruang-render');
    Route::post('/infrastruktur/ruang', [App\Http\Controllers\Master\Infrastruktur\RuangController::class, 'handleRuang'])->name('infrastruktur.ruang-handle');
    Route::patch('/infrastruktur/ruang/{code}', [App\Http\Controllers\Master\Infrastruktur\RuangController::class, 'updateRuang'])->name('infrastruktur.ruang-update');
    Route::delete('/infrastruktur/ruang/{code}', [App\Http\Controllers\Master\Infrastruktur\RuangController::class, 'deleteRuang'])->name('infrastruktur.ruang-delete');

    // MASTER INFRASTRUKTUR => KATEGORI BARANG
    Route::get('/infrastruktur/kategori-barang', [App\Http\Controllers\Master\Infrastruktur\KategoriBarangController::class, 'renderKategoriBarang'])->name('infrastruktur.kategori-barang-render');
    Route::post('/infrastruktur/kategori-barang', [App\Http\Controllers\Master\Infrastruktur\KategoriBarangController::class, 'handleKategoriBarang'])->name('infrastruktur.kategori-barang-handle');
    Route::patch('/infrastruktur/kategori-barang/{code}', [App\Http\Controllers\Master\Infrastruktur\KategoriBarangController::class, 'updateKategoriBarang'])->name('infrastruktur.kategori-barang-update');
    Route::delete('/infrastruktur/kategori-barang/{code}', [App\Http\Controllers\Master\Infrastruktur\KategoriBarangController::class, 'deleteKategoriBarang'])->name('infrastruktur.kategori-barang-delete');

    // MASTER INFRASTRUKTUR => BARANG
    Route::get('/infrastruktur/barang', [App\Http\Controllers\Master\Infrastruktur\BarangController::class, 'renderBarang'])->name('infrastruktur.barang-render');
    Route::post('/infrastruktur/barang', [App\Http\Controllers\Master\Infrastruktur\BarangController::class, 'handleBarang'])->name('infrastruktur.barang-handle');
    Route::patch('/infrastruktur/barang/{code}', [App\Http\Controllers\Master\Infrastruktur\BarangController::class, 'updateBarang'])->name('infrastruktur.barang-update');
    Route::delete('/infrastruktur/barang/{code}', [App\Http\Controllers\Master\Infrastruktur\BarangController::class, 'deleteBarang'])->name('infrastruktur.barang-delete');

    // MASTER INFRASTRUKTUR => MUTASI BARANG
    Route::get('/infrastruktur/mutasi-barang', [App\Http\Controllers\Master\Infrastruktur\MutasiBarangController::class, 'renderMutasiBarang'])->name('infrastruktur.mutasi-barang-render');
    Route::post('/infrastruktur/mutasi-barang', [App\Http\Controllers\Master\Infrastruktur\MutasiBarangController::class, 'handleMutasiBarang'])->name('infrastruktur.mutasi-barang-handle');
    Route::patch('/infrastruktur/mutasi-barang/{code}', [App\Http\Controllers\Master\Infrastruktur\MutasiBarangController::class, 'updateMutasiBarang'])->name('infrastruktur.mutasi-barang-update');
    Route::delete('/infrastruktur/mutasi-barang/{code}', [App\Http\Controllers\Master\Infrastruktur\MutasiBarangController::class, 'deleteMutasiBarang'])->name('infrastruktur.mutasi-barang-delete');

    // MASTER INFRASTRUKTUR => PENGADAAN BARANG
    Route::get('/infrastruktur/pengadaan-barang', [App\Http\Controllers\Master\Infrastruktur\PengadaanBarangController::class, 'renderPengadaanBarang'])->name('infrastruktur.pengadaan-barang-render');
    Route::post('/infrastruktur/pengadaan-barang', [App\Http\Controllers\Master\Infrastruktur\PengadaanBarangController::class, 'handlePengadaanBarang'])->name('infrastruktur.pengadaan-barang-handle');
    Route::patch('/infrastruktur/pengadaan-barang/{code}', [App\Http\Controllers\Master\Infrastruktur\PengadaanBarangController::class, 'updatePengadaanBarang'])->name('infrastruktur.pengadaan-barang-update');
    Route::delete('/infrastruktur/pengadaan-barang/{code}', [App\Http\Controllers\Master\Infrastruktur\PengadaanBarangController::class, 'deletePengadaanBarang'])->name('infrastruktur.pengadaan-barang-delete');

    // MASTER INFRASTRUKTUR => INVENTARIS BARANG
    Route::get('/infrastruktur/inventaris-barang', [App\Http\Controllers\Master\Infrastruktur\InventarisBarangController::class, 'renderInventarisBarang'])->name('infrastruktur.inventaris-barang-render');
    Route::post('/infrastruktur/inventaris-barang', [App\Http\Controllers\Master\Infrastruktur\InventarisBarangController::class, 'handleInventarisBarang'])->name('infrastruktur.inventaris-barang-handle');
    Route::patch('/infrastruktur/inventaris-barang/{code}', [App\Http\Controllers\Master\Infrastruktur\InventarisBarangController::class, 'updateInventarisBarang'])->name('infrastruktur.inventaris-barang-update');
    Route::delete('/infrastruktur/inventaris-barang/{code}', [App\Http\Controllers\Master\Infrastruktur\InventarisBarangController::class, 'deleteInventarisBarang'])->name('infrastruktur.inventaris-barang-delete');

    // MASTER KEUANGAN => SALDO
    Route::get('/keuangan/saldo', [App\Http\Controllers\Master\Keuangan\SaldoController::class, 'renderSaldo'])->name('keuangan.saldo-render');
    Route::post('/keuangan/saldo', [App\Http\Controllers\Master\Keuangan\SaldoController::class, 'handleSaldo'])->name('keuangan.saldo-handle');
    Route::patch('/keuangan/saldo/{code}', [App\Http\Controllers\Master\Keuangan\SaldoController::class, 'updateSaldo'])->name('keuangan.saldo-update');
    Route::delete('/keuangan/saldo/{code}', [App\Http\Controllers\Master\Keuangan\SaldoController::class, 'deleteSaldo'])->name('keuangan.saldo-delete');

    // MASTER KEUANGAN => TAGIHAN KULIAH GROUP
    Route::get('/keuangan/tagihan-kuliah-group', [App\Http\Controllers\Master\Keuangan\TagihanKuliahGroupController::class, 'renderTagihanKuliahGroup'])->name('keuangan.tagihan-kuliah-group-render');
    Route::post('/keuangan/tagihan-kuliah-group', [App\Http\Controllers\Master\Keuangan\TagihanKuliahGroupController::class, 'handleTagihanKuliahGroup'])->name('keuangan.tagihan-kuliah-group-handle');
    Route::patch('/keuangan/tagihan-kuliah-group/{code}', [App\Http\Controllers\Master\Keuangan\TagihanKuliahGroupController::class, 'updateTagihanKuliahGroup'])->name('keuangan.tagihan-kuliah-group-update');
    Route::delete('/keuangan/tagihan-kuliah-group/{code}', [App\Http\Controllers\Master\Keuangan\TagihanKuliahGroupController::class, 'deleteTagihanKuliahGroup'])->name('keuangan.tagihan-kuliah-group-delete');
    Route::post('/keuangan/tagihan-kuliah-group/{code}/publish',[App\Http\Controllers\Master\Keuangan\TagihanKuliahGroupController::class, 'publishTagihanKuliahGroup'])->name('keuangan.tagihan-kuliah-group-publish');
    Route::post('/keuangan/tagihan-kuliah-group/{code}/archive',[App\Http\Controllers\Master\Keuangan\TagihanKuliahGroupController::class, 'archiveTagihanKuliahGroup'])->name('keuangan.tagihan-kuliah-group-archive');
    Route::get('/keuangan/tagihan-kuliah-group/{code}/detail',[App\Http\Controllers\Master\Keuangan\TagihanKuliahGroupController::class, 'viewTagihanDetail'])->name('keuangan.tagihan-kuliah-group-detail');
