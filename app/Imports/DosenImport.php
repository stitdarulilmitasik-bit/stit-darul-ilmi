<?php

namespace App\Imports;

use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class DosenImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $columns = Schema::getColumnListing('dosens');
        $ignored = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

        foreach ($rows as $row) {
            $data = [];
            foreach ($columns as $column) {
                if (in_array($column, $ignored, true) || !array_key_exists($column, $row->toArray())) {
                    continue;
                }
                $value = $row[$column];
                if ($value !== null && $value !== '') {
                    $data[$column] = $value;
                }
            }

            if (empty($data['name']) || empty($data['email']) || empty($data['phone'])) {
                continue;
            }

            $existing = !empty($data['code'])
                ? Dosen::withTrashed()->where('code', $data['code'])->first()
                : (!empty($data['email']) ? Dosen::withTrashed()->where('email', $data['email'])->first() : null);

            if (isset($data['password']) && $data['password'] !== '') {
                $data['password'] = Hash::make($data['password']);
            }

            if (empty($data['code'])) {
                $data['code'] = 'DSN-' . strtoupper(Str::random(8));
            }

            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                }
                $existing->update($data);
            } else {
                Dosen::create($data);
            }
        }
    }
}
