<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;
        $now = now();

        DB::table('tenants')->insert([
            ['user_id' => $userId, 'name' => 'Marie Dupont', 'email' => 'marie.dupont@email.com', 'phone' => '06 12 34 56 78', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $userId, 'name' => 'Thomas Martin', 'email' => 'thomas.martin@email.com', 'phone' => '06 23 45 67 89', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $userId, 'name' => 'Sophie Bernard', 'email' => 'sophie.bernard@email.com', 'phone' => '06 34 56 78 90', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $userId, 'name' => 'Lucas Petit', 'email' => 'lucas.petit@email.com', 'phone' => '06 45 67 89 01', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
