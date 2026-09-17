<?php

namespace App\Models\Pengaturan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasLogAktivitas;
use App\Models\User;

class HomepageSection extends Model
{
    use SoftDeletes, HasLogAktivitas;

    protected $table = 'homepage_sections';
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
