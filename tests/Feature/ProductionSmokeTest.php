<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class ProductionSmokeTest extends TestCase
{
    public function test_critical_named_routes_are_registered(): void
    {
        foreach ([
            'web-admin.akademik.krs-render',
            'web-admin.akademik.krs-print',
            'web-admin.akademik.khs-render',
            'web-admin.akademik.khs-print',
            'web-admin.akademik.nilai-render',
            'mahasiswa.akademik.krs-render',
            'mahasiswa.akademik.krs-cetak',
            'mahasiswa.akademik.khs',
            'mahasiswa.akademik.khs.cetak',
            'mahasiswa.layanan.transkrip.cetak',
            'root.visi-misi',
            'root.struktur',
        ] as $name) {
            $this->assertTrue(Route::has($name), "Missing route: {$name}");
        }
    }
}
