# SIAKAD STIT Darul Ilmi

Sistem Informasi Akademik untuk mendukung layanan akademik dan administrasi STIT Darul Ilmi.

## Teknologi

- Laravel 12
- PHP 8.2+
- Livewire 3
- Vite
- Tabler UI

## Instalasi singkat

1. Salin `.env.example` menjadi `.env` dan isi konfigurasi aplikasi/database.
2. Jalankan `composer install`.
3. Jalankan `php artisan key:generate`.
4. Jalankan migrasi sesuai lingkungan: `php artisan migrate`.
5. Jika membutuhkan data awal: `php artisan db:seed`.
6. Buat symbolic link asset upload: `php artisan storage:link`.
7. Bersihkan cache: `php artisan optimize:clear`.
8. Untuk asset frontend, jalankan `npm install` lalu `npm run build`.

## Asset gambar

Asset branding resmi yang tersedia di repository berada di `public/images/branding/`. Placeholder profesional berada di `public/images/placeholders/`. Foto yang diunggah melalui aplikasi tetap disimpan pada `storage/app/public/images/`.

Jangan menggunakan foto stock/random sebagai foto pimpinan, dosen, mahasiswa, atau kegiatan kampus. Gunakan asset resmi STIT Darul Ilmi atau placeholder sampai asset resmi tersedia.

## Konfigurasi kontak

Alamat website, email, telepon, media sosial, dan data pimpinan harus diisi melalui pengaturan aplikasi menggunakan data resmi STIT Darul Ilmi. Repository ini tidak mengasumsikan data kontak resmi yang tidak tersedia.
