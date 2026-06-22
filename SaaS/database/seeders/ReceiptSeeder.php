<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReceiptSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $months = ['2025-01', '2025-02', '2025-03', '2025-04', '2025-05', '2025-06'];

        foreach ([1 => [950, 80, 1030], 2 => [520, 45, 565]] as $cId => [$rent, $charges, $total]) {
            foreach ($months as $month) {
                DB::table('receipts')->insert([
                    'contract_id' => $cId,
                    'reference' => "QUIT-$cId-" . str_replace('-', '', $month),
                    'period' => "$month-01",
                    'rent' => $rent,
                    'charges' => $charges,
                    'total' => $total,
                    'due_date' => "$month-05",
                    'payment_date' => "$month-03",
                    'status' => 'paye',
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }
    }
}
