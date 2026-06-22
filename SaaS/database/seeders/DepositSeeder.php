<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepositSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('deposits')->insert([
            ['contract_id' => 1, 'amount' => 950, 'received_at' => '2024-06-01', 'returned_at' => null, 'returned_amount' => null, 'status' => 'retenu', 'created_at' => now(), 'updated_at' => now()],
            ['contract_id' => 2, 'amount' => 520, 'received_at' => '2024-09-01', 'returned_at' => null, 'returned_amount' => null, 'status' => 'retenu', 'created_at' => now(), 'updated_at' => now()],
            ['contract_id' => 3, 'amount' => 620, 'received_at' => '2025-01-15', 'returned_at' => null, 'returned_amount' => null, 'status' => 'retenu', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
