<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Production-safe database seeder.
     *
     * No fabricated users, academic records, news, announcements, schedules,
     * facilities, PMB records or other demo content are inserted automatically.
     * The first administrator and verified campus data are created through the
     * application setup wizard and the protected admin modules.
     */
    public function run(): void
    {
        // Intentionally empty.
        // Run the setup wizard after migration to create the first administrator.
    }
}
