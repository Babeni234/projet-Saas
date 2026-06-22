<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentRevisionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('rent_revisions')->insert([
            [
                'contract_id' => 1, 'landlord_id' => 1,
                'previous_rent' => 920, 'new_rent' => 950,
                'percentage_change' => 3.26, 'index_name' => 'IRL',
                'index_value_old' => 130.57, 'index_value_new' => 134.78,
                'effective_date' => '2025-01-01', 'status' => 'appliquee',
                'applied_at' => '2025-01-01', 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'contract_id' => 2, 'landlord_id' => 1,
                'previous_rent' => 505, 'new_rent' => 520,
                'percentage_change' => 2.97, 'index_name' => 'IRL',
                'index_value_old' => 130.57, 'index_value_new' => 134.45,
                'effective_date' => '2025-01-01', 'status' => 'projet',
                'applied_at' => null, 'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }
}
