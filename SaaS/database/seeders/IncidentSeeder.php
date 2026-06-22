<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('incidents')->insert([
            [
                'property_id' => 1, 'contract_id' => 1, 'tenant_id' => 1, 'landlord_id' => 1,
                'title' => 'Fuite sous l\'évier de la cuisine', 'description' => 'Fuite d\'eau persistante sous l\'évier.',
                'category' => 'plomberie', 'urgency' => 'haute', 'status' => 'en_cours',
                'resolved_at' => null, 'resolution_notes' => null,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'property_id' => 2, 'contract_id' => 2, 'tenant_id' => 2, 'landlord_id' => 1,
                'title' => 'Chauffage qui ne s\'éteint pas', 'description' => 'Le radiateur reste allumé en permanence.',
                'category' => 'chauffage', 'urgency' => 'moyenne', 'status' => 'ouvert',
                'resolved_at' => null, 'resolution_notes' => null,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'property_id' => 3, 'contract_id' => 3, 'tenant_id' => 3, 'landlord_id' => 1,
                'title' => 'Prise électrique défectueuse', 'description' => 'La prise du salon ne fonctionne plus.',
                'category' => 'electricite', 'urgency' => 'basse', 'status' => 'resolu',
                'resolved_at' => '2025-06-10', 'resolution_notes' => 'Prise remplacée par un électricien.',
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }
}
