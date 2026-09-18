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
            0, 1, 2, 3, 4, 5, 6, 7 => 'web-admin.',
            default => 'unknown.',
        };
    }
}
