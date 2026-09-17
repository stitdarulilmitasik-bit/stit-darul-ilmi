# SIAKAD Production Patch

## Included
- Production bootstrap for Laravel 12 (`bootstrap/app.php`, `bootstrap/providers.php`).
- Middleware aliases for authentication/setup and role checks.
- Full Web Administrator master-core route set remains enabled.
- KRS: create/update/delete, course add/remove, approval, publish/lock flow, bulk actions, detail, PDF printing.
- KHS: create/generate/regenerate, update/finalize/publish/lock, bulk generation/publish, detail, PDF printing, transcript PDF.
- Nilai: CRUD, import/export, publish/lock, bulk update/publish, detail.
- Student KRS PDF, student KHS page/PDF, and student transcript page/PDF.
- Fixed route ordering so `/print` and `/detail` are not captured by `{code}` routes.
- Added missing admin detail views and publication detail views.
- Added custom 404 view and exception renderer.
- Fixed missing controller method aliases referenced by master routes.
- Fixed several database-column mismatches in student academic queries.
- Fixed missing/incorrect model imports in student academic controller.
- Added smoke test for critical route registration.

## Installation
1. Extract the ZIP into the application directory.
2. Run `composer install --no-dev --optimize-autoloader`.
3. Configure `.env` and database credentials.
4. Run `php artisan key:generate` if the app key is empty.
5. Run `php artisan migrate --seed` on a new database, or `php artisan migrate` on an existing database.
6. Run `php artisan storage:link`.
7. Run `php artisan optimize:clear` then `php artisan config:cache` and `php artisan route:cache` for production.

## PDF
PDF output uses `barryvdh/laravel-dompdf`, already declared in `composer.json`/`composer.lock`.

## Important data note
The original schema has no academic attendance/presence table. The student attendance screen therefore fails safely with an empty attendance dataset instead of querying a nonexistent table. A dedicated academic attendance module should be added if per-meeting attendance is required.
