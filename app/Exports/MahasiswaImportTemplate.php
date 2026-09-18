<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MahasiswaImportTemplate implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'Nama',
            'NIM',
            'Email',
            'Nomor Telepon',
            'Program Studi',
            'Semester',
            'Angkatan',
            'Status',
            'Password',
        ];
    }

    public function array(): array
    {
        return [[
            'Contoh Nama Mahasiswa',
            '20260001',
            'mahasiswa@example.com',
            '081234567890',
            'Nama Program Studi',
            1,
            2026,
            'Mahasiswa Aktif',
            'password123',
        ]];
    }
}
