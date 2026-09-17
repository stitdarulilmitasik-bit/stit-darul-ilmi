<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('stit_image_url')) {
    /**
     * Resolve a public image path and return a safe fallback when the file is missing.
     * Supports paths stored in the public storage disk as well as public/ assets.
     */
    function stit_image_url(?string $path, string $fallback = 'images/placeholders/news-placeholder.svg'): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return asset(ltrim($fallback, '/'));
        }

        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        // Normalize common values that were historically stored in the database.
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        // Never make the public site depend on an old/template image host.
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return asset(ltrim($fallback, '/'));
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        if (is_file(public_path($path))) {
            return asset($path);
        }

        return asset(ltrim($fallback, '/'));
    }
}

if (! function_exists('stit_storage_image_url')) {
    function stit_storage_image_url(?string $directory, ?string $filename, string $fallback = 'images/placeholders/news-placeholder.svg'): string
    {
        $directory = trim((string) $directory, '/');
        $filename = trim((string) $filename);

        return stit_image_url(
            $filename === '' ? null : ($directory !== '' ? $directory . '/' . ltrim($filename, '/') : ltrim($filename, '/')),
            $fallback
        );
    }
}

if (! function_exists('stit_profile_image_url')) {
    function stit_profile_image_url(?string $filename): string
    {
        return stit_storage_image_url('images/profile', $filename, 'images/placeholders/profile-placeholder.svg');
    }
}

if (! function_exists('stit_news_image_url')) {
    function stit_news_image_url(?string $filename): string
    {
        return stit_storage_image_url('images/berita', $filename);
    }
}

if (! function_exists('stit_announcement_image_url')) {
    function stit_announcement_image_url(?string $filename): string
    {
        return stit_storage_image_url('images/pengumuman', $filename);
    }
}

if (! function_exists('stit_gallery_image_url')) {
    function stit_gallery_image_url(?string $filename): string
    {
        return stit_storage_image_url('images/galeri', $filename);
    }
}

if (! function_exists('stit_gallery_photo_url')) {
    function stit_gallery_photo_url(?string $filename): string
    {
        return stit_storage_image_url('images/galeri/foto', $filename);
    }
}
