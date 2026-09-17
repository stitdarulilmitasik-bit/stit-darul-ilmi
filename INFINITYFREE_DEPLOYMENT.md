# Deployment STIT Darul Ilmi ke InfinityFree

Branch ini disiapkan khusus untuk deployment Laravel 12 ke InfinityFree.

## Perubahan yang sudah disiapkan

- `public/index.php` dipulihkan sebagai Laravel front controller.
- `.htaccess` di root ditambahkan untuk menyesuaikan document root InfinityFree (`htdocs`) dengan folder `public` Laravel.
- Akses `/storage/...` diarahkan ke `storage/app/public/...` karena InfinityFree tidak mendukung symbolic link Laravel.
- `public/.htaccess` Laravel tetap digunakan untuk routing aplikasi.

## Struktur upload

InfinityFree menggunakan `htdocs` sebagai document root. Karena itu, upload **seluruh project Laravel** ke dalam `htdocs`, bukan hanya isi folder `public`.

Struktur akhirnya:

```text
htdocs/
├── .env
├── .htaccess
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── index.php
│   └── .htaccess
├── resources/
├── routes/
├── storage/
├── vendor/
└── ...
```

## Persiapan di komputer lokal

Jalankan dari folder project:

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

Jangan upload `node_modules`.

Jika InfinityFree menolak file PHP besar, gunakan autoloader tanpa optimasi:

```bash
composer dump-autoload --no-dev --optimize=false
```

InfinityFree mendokumentasikan batasan upload dan kemungkinan masalah pada `vendor/composer/autoload_classmap.php` yang terlalu besar.

## File `.env`

Buat `.env` production di server. Jangan commit `.env` berisi password database atau API key ke GitHub.

Minimal ubah:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://DOMAIN-ANDA

SESSION_DRIVER=file
CACHE_STORE=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
```

Untuk database, gunakan host, nama database, username, dan password yang diberikan InfinityFree pada menu MySQL Databases. Jangan menganggap host database selalu `localhost`.

Untuk email, gunakan SMTP eksternal jika fitur email dibutuhkan.

## Database

InfinityFree tidak menyediakan akses terminal Artisan pada free hosting. Export database lokal menjadi `.sql`, lalu import melalui phpMyAdmin InfinityFree.

Jangan menjalankan migrasi production tanpa backup database.

## Storage upload

Jangan mengandalkan `php artisan storage:link` di InfinityFree. Root `.htaccess` yang sudah disediakan repository memetakan:

```text
/storage/nama-file.jpg
```

ke:

```text
/storage/app/public/nama-file.jpg
```

Pastikan folder berikut ada dan dapat ditulisi oleh aplikasi:

```text
storage/app/public
storage/framework/cache
storage/framework/sessions
storage/framework/views
storage/logs
bootstrap/cache
```

## Cache production

Sebelum membuat ZIP deployment, bersihkan cache lokal:

```bash
php artisan optimize:clear
```

Jika server tidak menyediakan Artisan, jangan upload cache konfigurasi/routing/view dari komputer lokal yang masih mengandung path atau `.env` lokal.

## Vite

InfinityFree tidak menjalankan `npm`. Asset frontend harus sudah dibuild di komputer lokal dengan:

```bash
npm run build
```

Setelah build, periksa folder output Vite yang digunakan oleh project dan pastikan seluruh asset hasil build ikut ter-upload.

## Batasan InfinityFree yang perlu diperhatikan

Menurut dokumentasi InfinityFree terbaru, free hosting menggunakan PHP 8.3 dan menyediakan MySQL/MariaDB serta dukungan `.htaccess`. Laravel 12 pada repository ini mensyaratkan PHP 8.2 atau lebih baru, sehingga versi PHP tersebut sesuai.

Free hosting tidak menyediakan worker background Laravel atau cron yang dapat diandalkan untuk menjalankan `queue:work` dan `schedule:run`. Karena itu konfigurasi `QUEUE_CONNECTION=sync` lebih aman jika aplikasi membutuhkan queue pada free hosting.

## Checklist setelah upload

1. Pilih PHP 8.3 pada pengaturan hosting.
2. Upload seluruh project ke `htdocs`.
3. Pastikan `.htaccess` root dan `public/.htaccess` ada.
4. Pastikan `public/index.php` ada.
5. Upload `vendor` hasil `composer install --no-dev`.
6. Upload asset hasil `npm run build`.
7. Buat `.env` production dengan kredensial database InfinityFree.
8. Import database melalui phpMyAdmin.
9. Pastikan `storage/app/public` dan folder cache Laravel tersedia.
10. Aktifkan HTTPS dan isi `APP_URL` dengan domain HTTPS.
11. Uji halaman utama, login, dashboard, upload gambar, galeri, PDF, dan koneksi database.

## Catatan keamanan

Jangan mengaktifkan `APP_DEBUG=true` di production dan jangan menyimpan `.env`, password database, Midtrans production key, SMTP password, atau Turnstile secret di repository publik.
