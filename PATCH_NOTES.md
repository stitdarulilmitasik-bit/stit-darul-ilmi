# SIAKAD Repair Notes

Perbaikan utama:
- Memperbaiki halaman KRS mahasiswa yang sebelumnya berhenti karena `dd()` dan relasi/kolom tidak sesuai.
- Menambahkan tambah/batalkan mata kuliah KRS mahasiswa.
- Menambahkan cetak KRS mahasiswa A4 dan cetak KRS administrator.
- Menambahkan alias atribut untuk perbedaan nama kolom database (`code/name`) dengan tampilan (`kode_mk/nama`, `nama_kelas`, `nama_lengkap`).
- Mengaktifkan seluruh route menu mahasiswa: Akademik, Keuangan, Layanan, Informasi, dan Bantuan tanpa route kosong.
- Memperbaiki referensi route jadwal/presensi yang tidak konsisten.
- Menambahkan halaman publik untuk Profil, Visi & Misi, Struktur Organisasi, Fasilitas, Kontak, serta tautan menu publik lain agar tidak berhenti pada 404.
- Mengganti tautan hard-coded yang menuju `/siakad` dan halaman layanan yang belum memiliki route dengan route yang tersedia.
- Menambahkan halaman nilai per semester yang sebelumnya dirujuk controller tetapi file view tidak tersedia.

Catatan:
- Isi Visi, Misi, dan Struktur Organisasi pada `app/Http/Controllers/PublicInfoController.php` adalah konten awal yang dapat disesuaikan dengan dokumen resmi kampus.
- Dependensi Composer tidak disertakan dalam ZIP; jalankan `composer install` di server jika folder `vendor` belum tersedia.
