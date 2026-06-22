<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subscriptions')->insert([
            ['contract_id' => 1, 'payment_method_id' => 1, 'status' => 'actif', 'amount' => 1030, 'frequency' => 'mensuel', 'day_of_month' => 5, 'next_payment_date' => '2025-07-05', 'last_payment_date' => '2025-06-05', 'created_at' => now(), 'updated_at' => now()],
            ['contract_id' => 2, 'payment_method_id' => 2, 'status' => 'actif', 'amount' => 565, 'frequency' => 'mensuel', 'day_of_month' => 5, 'next_payment_date' => '2025-07-05', 'last_payment_date' => '2025-06-05', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
