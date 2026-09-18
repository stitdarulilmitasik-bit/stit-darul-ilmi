<?php

namespace App\Exports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DosenExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return Dosen::query()->orderBy('id');
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Email', 'Nomor Telepon', 'Status Kerja', 'Status Dosen'];
    }

    public function map($dosen): array
    {
        return [
            $dosen->id,
            $dosen->name,
            $dosen->email,
            $dosen->phone,
            $dosen->type,
            $dosen->status_dosen,
        ];
    }
}
