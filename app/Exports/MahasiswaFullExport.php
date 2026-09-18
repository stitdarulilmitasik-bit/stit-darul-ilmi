<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MahasiswaFullExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    private array $columns = [
    "id",
    "type",
    "semester",
    "taka_regist",
    "taka_active",
    "prodi_id",
    "kelas_id",
    "name",
    "photo",
    "username",
    "phone",
    "email",
    "link_ig",
    "link_fb",
    "link_in",
    "bio_blood",
    "bio_height",
    "bio_weight",
    "bio_gender",
    "bio_religion",
    "bio_placebirth",
    "bio_nationality",
    "bio_datebirth",
    "code",
    "password",
    "fst_setup",
    "tfa_setup",
    "ktp_addres",
    "ktp_rt",
    "ktp_rw",
    "ktp_village",
    "ktp_subdistrict",
    "ktp_poscode",
    "ktp_city",
    "ktp_province",
    "domicile_same",
    "domicile_addres",
    "domicile_rt",
    "domicile_rw",
    "domicile_village",
    "domicile_subdistrict",
    "domicile_poscode",
    "domicile_city",
    "domicile_province",
    "google_id",
    "google_token",
    "google_refresh_token",
    "title_front",
    "title_behind",
    "edu1_type",
    "edu1_place",
    "edu1_major",
    "edu1_average_score",
    "edu1_graduate_year",
    "edu2_type",
    "edu2_place",
    "edu2_major",
    "edu2_average_score",
    "edu2_graduate_year",
    "edu3_type",
    "edu3_place",
    "edu3_major",
    "edu3_average_score",
    "edu3_graduate_year",
    "numb_kk",
    "numb_ktp",
    "numb_nim",
    "numb_reg",
    "numb_nisn",
    "father_name",
    "father_datebirth",
    "father_lifestat",
    "father_education",
    "father_occupation",
    "father_income",
    "father_phone",
    "father_address",
    "mother_name",
    "mother_datebirth",
    "mother_lifestat",
    "mother_education",
    "mother_occupation",
    "mother_income",
    "mother_phone",
    "mother_address",
    "guard_name",
    "guard_nik",
    "guard_datebirth",
    "guard_relation",
    "guard_phone",
    "guard_address",
    "created_at",
    "updated_at",
    "deleted_at",
    "created_by",
    "updated_by",
    "deleted_by"
];

    public function query()
    {
        return Mahasiswa::withTrashed()->orderBy('id');
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function map($mahasiswa): array
    {
        $attributes = $mahasiswa->getAttributes();

        return array_map(
            fn ($column) => $attributes[$column] ?? null,
            $this->columns
        );
    }

    public function columnFormats(): array
    {
        $formats = [];
        foreach ($this->columns as $index => $column) {
            if (in_array($column, [
                'id', 'type', 'semester', 'taka_regist', 'taka_active',
                'prodi_id', 'kelas_id', 'fst_setup', 'tfa_setup',
                'created_by', 'updated_by', 'deleted_by'
            ], true)) {
                continue;
            }

            $formats[$this->columnLetter($index + 1)] = NumberFormat::FORMAT_TEXT;
        }

        return $formats;
    }

    private function columnLetter(int $number): string
    {
        $letter = '';

        while ($number > 0) {
            $mod = ($number - 1) % 26;
            $letter = chr(65 + $mod) . $letter;
            $number = intdiv($number - 1, 26);
        }

        return $letter;
    }
}
