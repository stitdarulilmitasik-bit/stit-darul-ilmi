<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Akademik\ProgramStudi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];
    public int $created = 0;
    public int $updated = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $excelRow = $index + 2;

            $name = trim((string) ($row['nama'] ?? ''));
            $nim = trim((string) ($row['nim'] ?? ''));
            $email = trim((string) ($row['email'] ?? ''));
            $phone = trim((string) ($row['nomor_telepon'] ?? $row['telepon'] ?? ''));
            $prodiName = trim((string) ($row['program_studi'] ?? ''));
            $semester = (int) ($row['semester'] ?? 0);
            $angkatan = trim((string) ($row['angkatan'] ?? ''));
            $status = trim((string) ($row['status'] ?? 'Mahasiswa Aktif'));
            $password = trim((string) ($row['password'] ?? ''));

            if ($name === '' || $nim === '' || $prodiName === '') {
                $this->errors[] = "Baris {$excelRow}: Nama, NIM, dan Program Studi wajib diisi.";
                continue;
            }

            if ($semester < 0 || $semester > 14) {
                $this->errors[] = "Baris {$excelRow}: Semester harus antara 0 sampai 14.";
                continue;
            }

            $prodi = ProgramStudi::whereRaw('LOWER(name) = ?', [Str::lower($prodiName)])->first();
            if (!$prodi) {
                $this->errors[] = "Baris {$excelRow}: Program Studi '{$prodiName}' tidak ditemukan.";
                continue;
            }

            $typeMap = [
                'calon mahasiswa' => 0,
                'mahasiswa aktif' => 1,
                'mahasiswa tidak aktif' => 2,
                'mahasiswa lulus' => 3,
                'mahasiswa cuti' => 4,
                'mahasiswa pindah' => 5,
            ];

            $typeKey = Str::lower($status);
            if (!array_key_exists($typeKey, $typeMap)) {
                $this->errors[] = "Baris {$excelRow}: Status '{$status}' tidak dikenali.";
                continue;
            }

            $takaRegist = 0;
            if ($angkatan !== '') {
                $year = (int) $angkatan;
                $takaRegist = $year >= 2000 && $year <= 2099 ? $year - 2000 : $year;
            }

            $existing = Mahasiswa::withTrashed()->where('numb_nim', $nim)->first();

            $data = [
                'name' => $name,
                'numb_nim' => $nim,
                'prodi_id' => $prodi->id,
                'semester' => $semester,
                'taka_regist' => $takaRegist,
                'type' => $typeMap[$typeKey],
                'updated_by' => Auth::guard('web')->id(),
            ];

            if ($phone !== '') {
                $data['phone'] = $phone;
            }

            if ($email !== '') {
                $data['email'] = $email;
            }

            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                }

                if ($password !== '') {
                    $data['password'] = Hash::make($password);
                }

                $existing->update($data);
                $this->updated++;
                continue;
            }

            if ($email === '' || $phone === '') {
                $this->errors[] = "Baris {$excelRow}: Email dan Nomor Telepon wajib diisi untuk mahasiswa baru.";
                continue;
            }

            if (Mahasiswa::where('email', $email)->exists()) {
                $this->errors[] = "Baris {$excelRow}: Email '{$email}' sudah digunakan.";
                continue;
            }

            if (Mahasiswa::where('phone', $phone)->exists()) {
                $this->errors[] = "Baris {$excelRow}: Nomor Telepon '{$phone}' sudah digunakan.";
                continue;
            }

            $data['code'] = 'MHS-' . strtoupper(Str::random(8));
            $data['password'] = Hash::make($password !== '' ? $password : $nim);
            $data['created_by'] = Auth::guard('web')->id();

            Mahasiswa::create($data);
            $this->created++;
        }
    }
}
