#!/bin/bash
set -e

echo "================================================"
echo " SIAKAD STIT DARUL ILMI - SETUP"
echo "================================================"

command -v composer >/dev/null 2>&1 || { echo "[ERROR] Composer tidak ditemukan di PATH."; exit 1; }

if [ ! -f .env ]; then
    echo "Membuat .env dari .env.example..."
    cp .env.example .env
else
    echo ".env sudah ada - tidak ditimpa."
fi

mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions

composer install
php artisan key:generate --ansi
php artisan storage:link

echo "Silakan periksa .env dan sesuaikan koneksi database jika diperlukan."
read -r -p "Tekan Enter untuk melanjutkan migrasi..."

php artisan migrate --seed
php artisan optimize:clear

echo "Setup selesai. Jalankan: php artisan serve"
