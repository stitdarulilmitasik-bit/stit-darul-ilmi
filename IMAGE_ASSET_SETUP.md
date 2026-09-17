# Perbaikan Asset Gambar STIT Darul Ilmi

Repository ini telah dibersihkan dari asset gambar demo/template dan referensi gambar eksternal pada halaman publik.

## Setelah deploy

Jalankan dari root project Laravel:

```bash
php artisan storage:link
php artisan optimize:clear
```

`storage:link` diperlukan agar foto berita, galeri, profil, dan asset upload lain di `storage/app/public` dapat diakses melalui `/storage/...`.

## Asset branding

Logo utama berada di `public/images/branding/` sehingga header/login tidak bergantung pada symbolic link storage. Placeholder profil dan berita berada di `public/images/placeholders/`.

## Catatan

Jangan mengisi foto dosen/mahasiswa/pimpinan dengan foto random. Gunakan foto resmi STIT Darul Ilmi atau biarkan placeholder sampai asset resmi tersedia.
