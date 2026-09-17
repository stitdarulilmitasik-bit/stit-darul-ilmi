@echo off
setlocal

echo ================================================
echo  SIAKAD STIT DARUL ILMI - SETUP
echo ================================================

where composer >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Composer tidak ditemukan di PATH.
    exit /b 1
)

if not exist .env (
    echo Membuat .env dari .env.example...
    copy /Y .env.example .env >nul
) else (
    echo .env sudah ada - tidak ditimpa.
)

if not exist storage\framework\views mkdir storage\framework\views
if not exist storage\framework\cache mkdir storage\framework\cache
if not exist storage\framework\sessions mkdir storage\framework\sessions

call composer install
if errorlevel 1 exit /b 1

php artisan key:generate --ansi
if errorlevel 1 exit /b 1

php artisan storage:link

call :wait_env

php artisan migrate --seed
if errorlevel 1 exit /b 1

php artisan optimize:clear

echo.
echo Setup selesai. Jalankan: php artisan serve
pause
exit /b 0

:wait_env
echo.
echo Silakan periksa .env dan sesuaikan koneksi database jika diperlukan.
pause
exit /b 0
