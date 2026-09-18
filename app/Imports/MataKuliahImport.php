<?php

namespace App\Imports;

use App\Models\Akademik\MataKuliah;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class MataKuliahImport implements ToCollection, WithHeadingRow
{
    protected array $columns;
    protected array $ignored = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    public function __construct()
    {
        $this->columns = array_values(array_diff(
            Schema::getColumnListing('mata_kuliahs'),
            $this->ignored
        ));
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $data = [];

            foreach ($this->columns as $column) {
                $value = $row->get($column);

                if ($value !== null && $value !== '') {
                    $data[$column] = $value;
                }
            }

            if (empty($data['name'])) {
                continue;
            }

            if (empty($data['code'])) {
                $data['code'] = 'MK-' . Str::upper(Str::random(8));
            }

            $existing = MataKuliah::withTrashed()->where('code', $data['code'])->first();

            if ($existing) {
                if (method_exists($existing, 'trashed') && $existing->trashed()) {
                    $existing->restore();
                }
                $existing->update($data);
            } else {
                MataKuliah::create($data);
            }
        }
    }
}
