<?php

namespace App\Exports;

use App\Models\Akademik\MataKuliah;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MataKuliahExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return MataKuliah::query()->with(['programStudi'])->orderBy('id');
    }

    public function headings(): array
    {
        return ['No', 'Nama Mata Kuliah', 'Kode', 'Program Studi', 'Semester', 'SKS', 'Status'];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->name,
            $item->code,
            $item->programStudi?->name,
            $item->semester,
            $item->bsks,
            $item->status,
        ];
    }
}
