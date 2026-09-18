<?php

namespace App\Helper;

trait roleTrait
{
    private function setPrefix()
    {
        $user = \Illuminate\Support\Facades\Auth::guard('web')->user();
        $rawType = $user?->raw_type;

        switch ($rawType) {
            case 1:
                return 'finance.';
            case 2:
                return 'officer.';
            case 3:
                return 'academic.';
            case 4:
                return 'admin.';
            case 5:
                return 'support.';
            default:
                return 'web-admin.';
        }
    }
}
