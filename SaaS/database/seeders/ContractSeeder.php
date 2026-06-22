<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('contracts')->insert([
            ['landlord_id' => 1, 'tenant_id' => 1, 'property_id' => 1, 'lease_type' => 'location_vide', 'duration' => 12, 'rent' => 950, 'charges' => 80, 'deposit' => 950, 'start_date' => '2024-06-01', 'end_date' => '2025-05-31', 'status' => 'active', 'deposit_paid' => true, 'created_at' => $now, 'updated_at' => $now],
            ['landlord_id' => 1, 'tenant_id' => 2, 'property_id' => 2, 'lease_type' => 'location_meublee', 'duration' => 12, 'rent' => 520, 'charges' => 45, 'deposit' => 520, 'start_date' => '2024-09-01', 'end_date' => '2025-08-31', 'status' => 'active', 'deposit_paid' => true, 'created_at' => $now, 'updated_at' => $now],
            ['landlord_id' => 1, 'tenant_id' => 3, 'property_id' => 3, 'lease_type' => 'location_vide', 'duration' => 12, 'rent' => 620, 'charges' => 60, 'deposit' => 620, 'start_date' => '2025-01-15', 'end_date' => '2025-12-31', 'status' => 'active', 'deposit_paid' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
