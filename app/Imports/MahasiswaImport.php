<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Akademik\ProgramStudi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];
    public int $created = 0;
    public int $updated = 0;

    /**
     * Import menggunakan struktur tabel mahasiswas secara langsung.
     * ID dipakai sebagai kunci utama jika tersedia pada Excel, kemudian
     * numb_nim dan code sebagai fallback.
     */
    protected array $ignoredColumns = [
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected array $dateColumns = [
        'bio_datebirth',
        'father_datebirth',
        'mother_datebirth',
        'guard_datebirth',
    ];

    protected function normalizeDateValue($value, int $excelRow, string $column): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_numeric($value) && (float) $value > 0) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Throwable $e) {
                $this->errors[] = "Baris {$excelRow}: Format tanggal {$column} '{$value}' tidak valid.";
                return null;
            }
        }

        $value = trim((string) $value);

        foreach (['d-m-Y', 'd/m/Y', 'Y-m-d', 'd.m.Y', 'm/d/Y'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                if ($date !== false && $date->format($format) === $value) {
                    return $date->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                // Coba format berikutnya.
            }
        }

        $this->errors[] = "Baris {$excelRow}: Format tanggal {$column} '{$value}' tidak valid.";
        return null;
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

    public function collection(Collection $rows)
    {
        $tableColumns = Schema::getColumnListing('mahasiswas');

        // Ambil semua kolom tabel mahasiswa agar Excel full-table dapat
        // diimport tanpa harus memelihara daftar kolom secara manual.
        $columns = array_values(array_diff($tableColumns, $this->ignoredColumns));

        foreach ($rows as $index => $row) {
            $row = $row->toArray();
            $excelRow = $index + 2;

            // Baris kosong/format kosong di bawah data tidak dianggap error.
            if ($this->isEmptyRow($row)) {
                continue;
            }

            $data = [];

            foreach ($columns as $column) {
                if (!array_key_exists($column, $row)) {
                    continue;
                }

                $value = $row[$column];

                if (is_string($value)) {
                    $value = trim($value);
                }

                if ($value === '') {
                    $value = null;
                }

                if ($value !== null && in_array($column, $this->dateColumns, true)) {
                    $value = $this->normalizeDateValue($value, $excelRow, $column);

                    if ($value === null) {
                        continue;
                    }
                }

                if ($value !== null) {
                    $data[$column] = $value;
                }
            }

            $id = isset($row['id']) && trim((string) $row['id']) !== ''
                ? (int) $row['id']
                : null;

            $nim = trim((string) ($row['numb_nim'] ?? ''));
            $code = trim((string) ($row['code'] ?? ''));
            $email = trim((string) ($row['email'] ?? ''));
            $phone = trim((string) ($row['phone'] ?? ''));

            // Untuk file full-table, ID adalah identitas utama.
            // Jika ID tidak tersedia, gunakan NIM lalu code.
            $existing = null;

            if ($id !== null && $id > 0) {
                $existing = Mahasiswa::withTrashed()->where('id', $id)->first();
            }

            if (!$existing && $nim !== '') {
                $existing = Mahasiswa::withTrashed()->where('numb_nim', $nim)->first();
            }

            if (!$existing && $code !== '') {
                $existing = Mahasiswa::withTrashed()->where('code', $code)->first();
            }

            // Jangan mencoba menyimpan ID dari Excel ke record yang berbeda.
            // Untuk record baru, ID tetap dikelola oleh database.
            unset($data['id']);

            if (empty($data['name']) || $nim === '') {
                $this->errors[] = "Baris {$excelRow}: kolom name dan numb_nim wajib diisi.";
                continue;
            }

            $data['numb_nim'] = $nim;

            if (isset($data['password']) && trim((string) $data['password']) !== '') {
                // Full export berisi password hash. Jangan hash ulang hash
                // yang sudah tersimpan; hash hanya password teks biasa.
                $passwordValue = trim((string) $data['password']);
                if (!preg_match('/^\$2[ayb]\$\d{2}\$/', $passwordValue)) {
                    $data['password'] = Hash::make($passwordValue);
                }
            } elseif ($existing) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($nim);
            }

            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                }

                // Cegah benturan UNIQUE code/email/phone dengan mahasiswa lain.
                foreach (['code', 'email', 'phone'] as $uniqueColumn) {
                    if (empty($data[$uniqueColumn])) {
                        continue;
                    }

                    $owner = Mahasiswa::withTrashed()
                        ->where($uniqueColumn, $data[$uniqueColumn])
                        ->where('id', '!=', $existing->id)
                        ->first();

                    if ($owner) {
                        if ($uniqueColumn === 'code') {
                            $this->errors[] = "Baris {$excelRow}: Code '{$data[$uniqueColumn]}' sudah digunakan mahasiswa lain (ID {$owner->id}). Code tidak diubah.";
                            unset($data[$uniqueColumn]);
                        } else {
                            $this->errors[] = "Baris {$excelRow}: {$uniqueColumn} '{$data[$uniqueColumn]}' sudah digunakan mahasiswa lain.";
                            continue 2;
                        }
                    }
                }

                $data['updated_by'] = Auth::guard('web')->id();
                $existing->update($data);
                $this->updated++;
                continue;
            }

            if ($email === '' || $phone === '') {
                $this->errors[] = "Baris {$excelRow}: Email dan Nomor Telepon wajib diisi untuk mahasiswa baru.";
                continue;
            }

            if ($code === '') {
                $data['code'] = 'MHS-' . strtoupper(Str::random(8));
                $code = $data['code'];
            }

            foreach (['email', 'phone', 'code', 'numb_nim'] as $uniqueColumn) {
                if (Mahasiswa::withTrashed()->where($uniqueColumn, $data[$uniqueColumn] ?? null)->exists()) {
                    $this->errors[] = "Baris {$excelRow}: {$uniqueColumn} '{$data[$uniqueColumn]}' sudah digunakan.";
                    continue 2;
                }
            }

            $data['created_by'] = Auth::guard('web')->id();
            Mahasiswa::create($data);
            $this->created++;
        }
    }
}
