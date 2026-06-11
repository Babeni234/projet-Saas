<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call IllustrationSeeder to seed test user, company, roles, agencies, and illustrations
        $this->call(IllustrationSeeder::class);
    }
}
