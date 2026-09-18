<?php

namespace App\Imports;

use App\Models\Akademik\MataKuliah;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MataKuliahImport implements ToCollection, WithHeadingRow
{
    protected array $ignored = [
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function collection(Collection $rows)
    {
        $columns = array_values(array_diff(
            Schema::getColumnListing('mata_kuliahs'),
            $this->ignored
        ));

        foreach ($rows as $row) {
            $row = $row->toArray();

            if ($this->isEmptyRow($row)) {
                continue;
            }

            $data = [];
            foreach ($columns as $column) {
                if (array_key_exists($column, $row)) {
                    $data[$column] = $row[$column];
                }
            }

            $id = $data['id'] ?? null;
            $code = $data['code'] ?? null;

            $existing = null;

            if (!empty($id)) {
                $existing = MataKuliah::withTrashed()->find($id);
            }

            if (!$existing && !empty($code)) {
                $existing = MataKuliah::withTrashed()->where('code', $code)->first();
            }

            unset($data['id']);

            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                }

                $existing->update($data);
            } else {
                if (empty($data['code'])) {
                    $data['code'] = 'MK-' . Str::upper(Str::random(8));
                }

                MataKuliah::create($data);
            }
        }
    }

    protected function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }
}
