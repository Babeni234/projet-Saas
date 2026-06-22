<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiscalYearSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('fiscal_years')->insert([
            ['user_id' => 1, 'year' => 2025, 'total_revenue' => 14280, 'total_expenses' => 3200, 'net_income' => 11080, 'status' => 'brouillon', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('fiscal_expenses')->insert([
            ['fiscal_year_id' => 1, 'property_id' => 1, 'category' => 'Travaux', 'description' => 'Remplacement chaudière T3 Lyon', 'amount' => 1800, 'expense_date' => '2025-03-15', 'deductible' => true, 'created_at' => $now, 'updated_at' => $now],
            ['fiscal_year_id' => 1, 'property_id' => 2, 'category' => 'Entretien', 'description' => 'Nettoyage studio Villeurbanne', 'amount' => 200, 'expense_date' => '2025-02-01', 'deductible' => true, 'created_at' => $now, 'updated_at' => $now],
            ['fiscal_year_id' => 1, 'property_id' => 3, 'category' => 'Honoraires', 'description' => 'Frais de gestion agence', 'amount' => 1200, 'expense_date' => '2025-04-01', 'deductible' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
