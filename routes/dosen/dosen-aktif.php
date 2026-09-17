<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dosen', 'middleware' => ['checkUser:Dosen Aktif'], 'as' => 'dosen.'],function(){
    Route::get('/logout', [App\Http\Controllers\AuthController::class, 'handleLogout'])->name('handle-logout');
    Route::get('/home',[App\Http\Controllers\Private\Dosen\RootController::class, 'renderDashboard'])->name('dashboard-render');
    Route::get('/profile',[App\Http\Controllers\Private\Dosen\RootController::class, 'renderProfile'])->name('profile-render');
    Route::patch('/profile',[App\Http\Controllers\Private\Dosen\RootController::class, 'handleProfile'])->name('profile-handle');

    Route::get('/akademik/master',[App\Http\Controllers\Private\Dosen\AkademikController::class, 'index'])->name('akademik.master');
    Route::post('/akademik/master/mata-kuliah',[App\Http\Controllers\Private\Dosen\AkademikController::class, 'storeMataKuliah'])->name('akademik.mata-kuliah.store');
    Route::patch('/akademik/master/mata-kuliah/{code}',[App\Http\Controllers\Private\Dosen\AkademikController::class, 'updateMataKuliah'])->name('akademik.mata-kuliah.update');
    Route::delete('/akademik/master/mata-kuliah/{code}',[App\Http\Controllers\Private\Dosen\AkademikController::class, 'deleteMataKuliah'])->name('akademik.mata-kuliah.delete');

    Route::get('/akademik/jadwal',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'jadwal'])->name('akademik.jadwal');
    Route::post('/akademik/jadwal',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'simpanJadwal'])->name('akademik.jadwal.store');
    Route::patch('/akademik/jadwal/{code}',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'updateJadwal'])->name('akademik.jadwal.update');
    Route::get('/akademik/nilai',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'nilai'])->name('akademik.nilai');
    Route::patch('/akademik/nilai/{code}',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'updateNilai'])->name('akademik.nilai.update');
    Route::get('/akademik/krs',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'krs'])->name('akademik.krs');
    Route::post('/akademik/krs/{code}/approve',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'approveKrs'])->name('akademik.krs.approve');
    Route::post('/akademik/krs/{code}/reject',[App\Http\Controllers\Private\Dosen\AkademikOperasionalController::class, 'rejectKrs'])->name('akademik.krs.reject');
});
