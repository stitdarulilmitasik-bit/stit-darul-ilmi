<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Akademik\ProgramStudi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];
    public int $created = 0;
    public int $updated = 0;

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

        $this->errors[] = "Baris {$excelRow}: Format tanggal {$column} '{$value}' tidak valid. Gunakan DD-MM-YYYY atau YYYY-MM-DD.";
        return null;
    }

    public function collection(Collection $rows)
    {
        $columns = [
            'type', 'semester', 'taka_regist', 'taka_active', 'prodi_id', 'kelas_id',
            'name', 'photo', 'username', 'phone', 'email', 'link_ig', 'link_fb', 'link_in',
            'bio_blood', 'bio_height', 'bio_weight', 'bio_gender', 'bio_religion',
            'bio_placebirth', 'bio_nationality', 'bio_datebirth',
            'code', 'password', 'fst_setup', 'tfa_setup',
            'ktp_addres', 'ktp_rt', 'ktp_rw', 'ktp_village', 'ktp_subdistrict',
            'ktp_poscode', 'ktp_city', 'ktp_province',
            'domicile_same', 'domicile_addres', 'domicile_rt', 'domicile_rw',
            'domicile_village', 'domicile_subdistrict', 'domicile_poscode',
            'domicile_city', 'domicile_province',
            'google_id', 'google_token', 'google_refresh_token',
            'title_front', 'title_behind',
            'edu1_type', 'edu1_place', 'edu1_major', 'edu1_average_score', 'edu1_graduate_year',
            'edu2_type', 'edu2_place', 'edu2_major', 'edu2_average_score', 'edu2_graduate_year',
            'edu3_type', 'edu3_place', 'edu3_major', 'edu3_average_score', 'edu3_graduate_year',
            'numb_kk', 'numb_ktp', 'numb_nim', 'numb_reg', 'numb_nisn',
            'father_name', 'father_datebirth', 'father_lifestat', 'father_education',
            'father_occupation', 'father_income', 'father_phone', 'father_address',
            'mother_name', 'mother_datebirth', 'mother_lifestat', 'mother_education',
            'mother_occupation', 'mother_income', 'mother_phone', 'mother_address',
            'guard_name', 'guard_nik', 'guard_datebirth', 'guard_relation',
            'guard_phone', 'guard_address',
        ];

        foreach ($rows as $index => $row) {
            $row = $row->toArray();
            $excelRow = $index + 2;

            $name = trim((string) ($row['name'] ?? $row['nama'] ?? ''));
            $nim = trim((string) ($row['numb_nim'] ?? $row['nim'] ?? ''));
            $email = trim((string) ($row['email'] ?? ''));
            $phone = trim((string) ($row['phone'] ?? $row['nomor_telepon'] ?? $row['telepon'] ?? ''));
            $password = trim((string) ($row['password'] ?? ''));

            if ($name === '' || $nim === '') {
                $this->errors[] = "Baris {$excelRow}: Nama dan NIM (numb_nim) wajib diisi.";
                continue;
            }

            $semester = (int) ($row['semester'] ?? 0);
            if ($semester < 0 || $semester > 14) {
                $this->errors[] = "Baris {$excelRow}: Semester harus antara 0 sampai 14.";
                continue;
            }

            $prodiId = (int) ($row['prodi_id'] ?? 0);
            if ($prodiId <= 0 && !empty($row['program_studi'])) {
                $prodiName = trim((string) $row['program_studi']);
                $prodi = ProgramStudi::whereRaw('LOWER(name) = ?', [Str::lower($prodiName)])->first();
                if (!$prodi) {
                    $this->errors[] = "Baris {$excelRow}: Program Studi '{$prodiName}' tidak ditemukan.";
                    continue;
                }
                $prodiId = $prodi->id;
            }

            if ($prodiId <= 0) {
                $this->errors[] = "Baris {$excelRow}: prodi_id wajib diisi.";
                continue;
            }

            $type = $row['type'] ?? null;
            if ($type === null || $type === '') {
                $status = Str::lower(trim((string) ($row['status'] ?? 'mahasiswa aktif')));
                $typeMap = [
                    'calon mahasiswa' => 0,
                    'mahasiswa aktif' => 1,
                    'mahasiswa tidak aktif' => 2,
                    'mahasiswa lulus' => 3,
                    'mahasiswa cuti' => 4,
                    'mahasiswa pindah' => 5,
                ];
                if (!array_key_exists($status, $typeMap)) {
                    $this->errors[] = "Baris {$excelRow}: Status '{$status}' tidak dikenali.";
                    continue;
                }
                $type = $typeMap[$status];
            }
            $type = (int) $type;

            if ($type < 0 || $type > 5) {
                $this->errors[] = "Baris {$excelRow}: type/status harus bernilai 0 sampai 5.";
                continue;
            }

            $takaRegist = (int) ($row['taka_regist'] ?? 0);
            if ($takaRegist === 0 && !empty($row['angkatan'])) {
                $year = (int) $row['angkatan'];
                $takaRegist = $year >= 2000 && $year <= 2099 ? $year - 2000 : $year;
            }

            $existing = Mahasiswa::withTrashed()->where('numb_nim', $nim)->first();

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

            $data['name'] = $name;
            $data['numb_nim'] = $nim;
            $data['prodi_id'] = $prodiId;
            $data['semester'] = $semester;
            $data['type'] = $type;
            $data['taka_regist'] = $takaRegist;

            $data['taka_active'] ??= 0;
            $data['kelas_id'] ??= 0;

            if ($phone !== '') {
                $data['phone'] = $phone;
            }

            if ($email !== '') {
                $data['email'] = $email;
            }

            foreach (['username', 'numb_kk', 'numb_ktp', 'numb_reg', 'numb_nisn'] as $uniqueNullable) {
                if (array_key_exists($uniqueNullable, $data) && $data[$uniqueNullable] === '') {
                    $data[$uniqueNullable] = null;
                }
            }

            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                }

                /*
                 * NIM adalah kunci pencocokan utama. Jika code dari Excel
                 * ternyata sudah dimiliki mahasiswa lain, jangan menimpa code
                 * milik mahasiswa tersebut. Data mahasiswa tetap di-update
                 * menggunakan record yang ditemukan berdasarkan NIM.
                 */
                if (!empty($data['code'])) {
                    $codeOwner = Mahasiswa::withTrashed()
                        ->where('code', $data['code'])
                        ->where('id', '!=', $existing->id)
                        ->first();

                    if ($codeOwner) {
                        $this->errors[] = "Baris {$excelRow}: Code '{$data['code']}' sudah digunakan mahasiswa lain (ID {$codeOwner->id}). Code mahasiswa '{$nim}' tidak diubah.";
                        unset($data['code']);
                    }
                }

                if ($password !== '') {
                    $data['password'] = Hash::make($password);
                } else {
                    unset($data['password']);
                }

                $data['updated_by'] = Auth::guard('web')->id();

                if (!empty($data['email'])) {
                    $emailExists = Mahasiswa::withTrashed()
                        ->where('email', $data['email'])
                        ->where('id', '!=', $existing->id)
                        ->exists();

                    if ($emailExists) {
                        $this->errors[] = "Baris {$excelRow}: Email '{$data['email']}' sudah digunakan mahasiswa lain.";
                        continue;
                    }
                }

                if (!empty($data['phone'])) {
                    $phoneExists = Mahasiswa::withTrashed()
                        ->where('phone', $data['phone'])
                        ->where('id', '!=', $existing->id)
                        ->exists();

                    if ($phoneExists) {
                        $this->errors[] = "Baris {$excelRow}: Nomor Telepon '{$data['phone']}' sudah digunakan mahasiswa lain.";
                        continue;
                    }
                }

                $existing->update($data);
                $this->updated++;
                continue;
            }

            if ($email === '' || $phone === '') {
                $this->errors[] = "Baris {$excelRow}: Email dan Nomor Telepon wajib diisi untuk mahasiswa baru.";
                continue;
            }

            if (Mahasiswa::withTrashed()->where('email', $email)->exists()) {
                $this->errors[] = "Baris {$excelRow}: Email '{$email}' sudah digunakan.";
                continue;
            }

            if (Mahasiswa::withTrashed()->where('phone', $phone)->exists()) {
                $this->errors[] = "Baris {$excelRow}: Nomor Telepon '{$phone}' sudah digunakan.";
                continue;
            }

            $data['code'] = !empty($row['code'])
                ? trim((string) $row['code'])
                : 'MHS-' . strtoupper(Str::random(8));

            $codeExists = Mahasiswa::withTrashed()->where('code', $data['code'])->exists();

            if ($codeExists) {
                $this->errors[] = "Baris {$excelRow}: Code '{$data['code']}' sudah digunakan mahasiswa lain. Data tidak dibuat.";
                continue;
            }

            $data['password'] = Hash::make($password !== '' ? $password : $nim);
            $data['created_by'] = Auth::guard('web')->id();

            Mahasiswa::create($data);
            $this->created++;
        }
    }
}
