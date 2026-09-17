<?php

namespace App\Models\Layanan;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CutiAkademik extends Model
{
    use SoftDeletes;

    protected $table = 'cuti_akademiks';
    protected $guarded = [];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
