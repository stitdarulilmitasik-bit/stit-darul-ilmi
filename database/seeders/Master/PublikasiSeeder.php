<?php

namespace Database\Seeders\Master;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Publikasi\Kategori;

class PublikasiSeeder extends Seeder
{
    /**
     * Seed only neutral publication categories.
     *
     * Public news, announcements, gallery items and academic calendar entries
     * are intentionally not fabricated here. They must be entered by the
     * authorized STIT Darul Ilmi administrator from verified campus data.
     */
    public function run(): void
    {
        $kategoris = [
            ['name' => 'Akademik', 'desc' => 'Informasi akademik resmi STIT Darul Ilmi Tasikmalaya.'],
            ['name' => 'Kemahasiswaan', 'desc' => 'Informasi kegiatan dan layanan kemahasiswaan resmi.'],
            ['name' => 'Beasiswa', 'desc' => 'Informasi beasiswa yang telah diverifikasi oleh kampus.'],
            ['name' => 'Kegiatan', 'desc' => 'Informasi kegiatan resmi STIT Darul Ilmi Tasikmalaya.'],
            ['name' => 'Prestasi', 'desc' => 'Informasi prestasi mahasiswa, dosen, dan institusi yang telah diverifikasi.'],
            ['name' => 'Kerja Sama', 'desc' => 'Informasi kerja sama resmi institusi.'],
            ['name' => 'Fasilitas', 'desc' => 'Informasi fasilitas dan pengembangan sarana kampus.'],
            ['name' => 'Alumni', 'desc' => 'Informasi kegiatan dan kontribusi alumni yang telah diverifikasi.'],
            ['name' => 'PMB', 'desc' => 'Informasi penerimaan mahasiswa baru yang telah ditetapkan kampus.'],
            ['name' => 'Wisuda', 'desc' => 'Informasi kegiatan wisuda resmi kampus.'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::firstOrCreate(
                ['slug' => Str::slug($kategori['name'])],
                [
                    'name' => $kategori['name'],
                    'code' => 'KTG-' . strtoupper(Str::random(8)),
                    'desc' => $kategori['desc'],
                    'created_by' => 1,
                ]
            );
        }
    }
}
