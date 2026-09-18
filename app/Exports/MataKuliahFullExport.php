<?php

namespace App\Exports;

use App\Models\Akademik\MataKuliah;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MataKuliahFullExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    protected array $columns;

    public function __construct()
    {
        $this->columns = Schema::getColumnListing('mata_kuliahs');
    }

    public function query()
    {
        return MataKuliah::withTrashed()->orderBy('id');
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function map($item): array
    {
        $attributes = $item->getAttributes();

        return array_map(
            fn ($column) => $attributes[$column] ?? null,
            $this->columns
        );
    }

    public function columnFormats(): array
    {
        $formats = [];

        foreach ($this->columns as $index => $column) {
            if ($column !== 'id' && !in_array($column, ['semester', 'kurikulum_id', 'prodi_id', 'requi_id', 'dosen1_id', 'dosen2_id', 'dosen3_id'])) {
                $formats[chr(65 + ($index % 26))] = NumberFormat::FORMAT_TEXT;
            }
        }

        return $formats;
    }
}
