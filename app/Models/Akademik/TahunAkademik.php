<?php

namespace App\Models\Akademik;

use App\Models\Akademik\KRS;
use App\Models\Akademik\Nilai;

// USE SYSTEM
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasLogAktivitas;

class TahunAkademik extends Model
{
    use SoftDeletes, HasLogAktivitas;

    protected $table = 'tahun_akademiks';
    protected $guarded = [];

    /**
     * Relasi Tahun Akademik ke Kelas
     */
    public function krs()
    {
        return $this->hasMany(KRS::class, 'taka_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'taka_id');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'taka_id', 'id');
    }
}