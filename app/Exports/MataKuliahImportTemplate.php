<?php

namespace App\Exports;

use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MataKuliahImportTemplate implements FromArray, WithHeadings, ShouldAutoSize
{
    protected array $columns;

    public function __construct()
    {
        $this->columns = array_values(array_diff(
            Schema::getColumnListing('mata_kuliahs'),
            ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by']
        ));
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function array(): array
    {
        return [array_fill(0, count($this->columns), '')];
    }
}
