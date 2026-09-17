<?php

namespace App\Models\Pengaturan;
// USE SYSTEM
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLogAktivitas;
// USE MODELS

class WebSetting extends Model
{
    use SoftDeletes, HasLogAktivitas;
    
    protected $table = 'web_settings';
    protected $guarded = [];

    public function getSchoolLogoHoriAttribute($value)
    {
        return $value == 'logo-hori.png' ? asset('images/branding/logo-hori.png') : stit_storage_image_url('images/logo', $value, 'images/branding/logo-hori.png');
    }
    public function getSchoolLogoVertAttribute($value)
    {
        return $value == 'logo-vert.png' ? asset('images/branding/logo-vert.png') : stit_storage_image_url('images/logo', $value, 'images/branding/logo-vert.png');
    }
}
