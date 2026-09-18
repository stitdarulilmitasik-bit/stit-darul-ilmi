<?php

namespace App\Imports;

use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements ToCollection, WithHeadingRow
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
            Schema::getColumnListing('dosens'),
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
            $email = $data['email'] ?? null;

            $existing = null;

            if (!empty($id)) {
                $existing = Dosen::withTrashed()->find($id);
            }

            if (!$existing && !empty($code)) {
                $existing = Dosen::withTrashed()->where('code', $code)->first();
            }

            if (!$existing && !empty($email)) {
                $existing = Dosen::withTrashed()->where('email', $email)->first();
            }

            unset($data['id']);

            if (array_key_exists('password', $data)) {
                $password = trim((string) $data['password']);

                if ($password === '') {
                    if ($existing) {
                        unset($data['password']);
                    } else {
                        $data['password'] = Hash::make($email ?: ($code ?: 'password'));
                    }
                } elseif (!preg_match('/^\$2[ayb]\$/', $password)) {
                    $data['password'] = Hash::make($password);
                }
            }

            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                }

                $existing->update($data);
            } else {
                if (empty($data['code'])) {
                    $data['code'] = 'DSN-' . strtoupper(\Illuminate\Support\Str::random(8));
                }

                Dosen::create($data);
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
