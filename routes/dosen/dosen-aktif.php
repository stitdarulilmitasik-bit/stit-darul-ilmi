<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dosen', 'middleware' => ['checkUser:Dosen Aktif', 'dosen.guard'], 'as' => 'dosen.'],function(){
    Route::get('/logout', [App\Http\Controllers\AuthController::class, 'handleLogout'])->name('handle-logout');
    Route::get('/home',[App\Http\Controllers\Private\Dosen\RootController::class, 'renderDashboard'])->name('dashboard-render');
    Route::get('/profile',[App\Http\Controllers\Private\Dosen\RootController::class, 'renderProfile'])->name('profile-render');
    Route::patch('/profile',[App\Http\Controllers\Private\Dosen\RootController::class, 'handleProfile'])->name('profile-handle');

    // Master Akademik Dosen: hak penuh dan controller yang sama dengan Admin.
    require __DIR__.'/master-akademik.php';

    // Operasional akademik khusus Dosen menggunakan URL terpisah agar tidak
    // menimpa route Master Akademik dengan URI yang sama.
    Route::get('/akademik/jadwal',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'jadwal'])->name('akademik.jadwal');
    Route::post('/akademik/jadwal',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'simpanJadwal'])->name('akademik.jadwal.store');
    Route::patch('/akademik/jadwal/{code}',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'updateJadwal'])->name('akademik.jadwal.update');
    Route::get('/akademik/nilai',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'nilai'])->name('akademik.nilai');
    Route::patch('/akademik/nilai/{code}',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'updateNilai'])->name('akademik.nilai.update');
    Route::get('/akademik/krs-operasional',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'krs'])->name('akademik.krs-operasional');
    Route::post('/akademik/krs-operasional/{code}/approve',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'approveKrs'])->name('akademik.krs-operasional.approve');
    Route::post('/akademik/krs-operasional/{code}/reject',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'rejectKrs'])->name('akademik.krs-operasional.reject');
});
