<?php

namespace App\Helper;

use Illuminate\Support\Facades\Auth;

trait roleTrait
{
    private function setPrefix()
    {
        $user = Auth::guard('web')->user();
        $rawType = $user?->raw_type;

        return match ((int) $rawType) {
            0 => 'web-admin.',
            1 => 'akademik.',
            2 => 'finance.',
            3 => 'kemahasiswaan.',
            4 => 'it.',
            5 => 'library.',
            6 => 'umum.',
            7 => 'admisi.',
            default => 'unknown.',
        };
    }
}
