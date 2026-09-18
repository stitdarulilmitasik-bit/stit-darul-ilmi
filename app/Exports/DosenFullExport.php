<?php

namespace App\Exports;

use App\Models\Dosen;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DosenFullExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    protected array $columns;

    public function __construct()
    {
        $this->columns = Schema::getColumnListing('dosens');
    }

    public function query()
    {
        return Dosen::withTrashed()->orderBy('id');
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function map($dosen): array
    {
        $attributes = $dosen->getAttributes();
        return array_map(fn ($column) => $attributes[$column] ?? null, $this->columns);
    }

    public function columnFormats(): array
    {
        $formats = [];
        foreach ($this->columns as $index => $column) {
            if (!in_array($column, ['id', 'type', 'created_by', 'updated_by', 'deleted_by'], true)) {
                $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
                $formats[$letter] = NumberFormat::FORMAT_TEXT;
            }
        }
        return $formats;
    }
}
