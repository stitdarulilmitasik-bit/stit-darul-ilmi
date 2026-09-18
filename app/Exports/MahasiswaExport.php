<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MahasiswaExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    private int $rowNumber = 0;
    public function query()
    {
        return Mahasiswa::query()
            ->with(['programStudi', 'tahunAkademikRegistrasi'])
            ->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'NIM',
            'Program Studi',
            'Nomor Telepon',
            'Semester',
            'Periode',
            'Angkatan',
            'Status',
        ];
    }

    public function map($mahasiswa): array
    {
        $semester = (int) ($mahasiswa->semester ?? 0);
        $periode = $semester > 0
            ? ($semester % 2 === 0 ? 'Genap' : 'Ganjil')
            : '-';

        $takaRegist = $mahasiswa->taka_regist;
        $angkatan = '-';

        if ($takaRegist !== null && $takaRegist !== '') {
            $value = (int) $takaRegist;
            $angkatan = $value > 0 && $value < 100 ? (string) (2000 + $value) : (string) $value;
        }

        return [
            ++$this->rowNumber,
            $mahasiswa->name ?? '-',
            (string) ($mahasiswa->numb_nim ?? ''),
            $mahasiswa->programStudi->name ?? '-',
            (string) ($mahasiswa->phone ?? ''),
            $semester > 0 ? $semester : '-',
            $periode,
            $angkatan,
            $mahasiswa->type ?? '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
