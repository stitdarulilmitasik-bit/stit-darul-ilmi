<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuti_akademiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->string('semester', 30);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('alasan');
            $table->string('alamat_selama_cuti')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('file_pendukung')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->enum('status', ['Diajukan', 'Diproses', 'Disetujui', 'Ditolak'])->default('Diajukan');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuti_akademiks');
    }
};
