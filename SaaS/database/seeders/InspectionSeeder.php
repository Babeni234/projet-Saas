<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InspectionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('inspections')->insert([
            [
                'contract_id' => 1, 'property_id' => 1, 'landlord_id' => 1, 'tenant_id' => 1,
                'type' => 'entree', 'inspection_date' => '2024-06-01', 'status' => 'termine',
                'rooms' => json_encode([
                    ['name' => 'Salon', 'items' => [['name' => 'Sol', 'condition' => 'Bon'], ['name' => 'Murs', 'condition' => 'Neuf']]],
                    ['name' => 'Cuisine', 'items' => [['name' => 'Plan de travail', 'condition' => 'Bon']]],
                ]),
                'notes' => 'État des lieux d\'entrée réalisé en présence du locataire. Tout est en bon état.',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'contract_id' => 2, 'property_id' => 2, 'landlord_id' => 1, 'tenant_id' => 2,
                'type' => 'entree', 'inspection_date' => '2024-09-01', 'status' => 'termine',
                'rooms' => json_encode([['name' => 'Studio', 'items' => [['name' => 'Sol', 'condition' => 'Correct'], ['name' => 'Meubles', 'condition' => 'Bon']]]]),
                'notes' => 'Meublé, état correct.',
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);

        DB::table('inspection_items')->insert([
            ['inspection_id' => 1, 'room' => 'Salon', 'item' => 'Sol stratifié', 'condition' => 'bon', 'comment' => 'Quelques micro-rayures', 'created_at' => $now, 'updated_at' => $now],
            ['inspection_id' => 1, 'room' => 'Salon', 'item' => 'Murs', 'condition' => 'neuf', 'comment' => 'Peinture fraîche', 'created_at' => $now, 'updated_at' => $now],
            ['inspection_id' => 1, 'room' => 'Cuisine', 'item' => 'Évier', 'condition' => 'bon', 'comment' => null, 'created_at' => $now, 'updated_at' => $now],
            ['inspection_id' => 2, 'room' => 'Studio', 'item' => 'Lit', 'condition' => 'bon', 'comment' => 'Matelas neuf', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
