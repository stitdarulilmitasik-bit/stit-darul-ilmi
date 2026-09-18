<?php

namespace App\Exports;

use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DosenImportTemplate implements FromArray, WithHeadings
{
    protected array $columns;

    public function __construct()
    {
        $this->columns = array_values(array_diff(
            Schema::getColumnListing('dosens'),
            ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by']
        ));
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function array(): array
    {
        return [[]];
    }
}
