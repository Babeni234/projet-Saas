<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsuranceGuaranteeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('insurance_guarantees')->insert([
            ['contract_id' => 1, 'tenant_id' => 1, 'type' => 'assurance', 'provider' => 'Groupama', 'reference_number' => 'ASS-2024-45678', 'covered_amount' => 95000, 'start_date' => '2024-06-01', 'end_date' => '2025-06-01', 'status' => 'actif', 'created_at' => now(), 'updated_at' => now()],
            ['contract_id' => 2, 'tenant_id' => 2, 'type' => 'visale', 'provider' => 'Action Logement', 'reference_number' => 'VIS-2024-12345', 'covered_amount' => 52000, 'start_date' => '2024-09-01', 'end_date' => '2026-09-01', 'status' => 'actif', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
