<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisitSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('visits')->insert([
            ['landlord_id' => 1, 'property_id' => 4, 'visitor_name' => 'Julie Dubois', 'visitor_email' => 'julie.dubois@email.com', 'visitor_phone' => '06 56 78 90 12', 'date' => '2025-06-20', 'time' => '14:30', 'status' => 'confirme', 'notes' => 'Couple avec enfant, intéressée par le jardin', 'created_at' => $now, 'updated_at' => $now],
            ['landlord_id' => 1, 'property_id' => 4, 'visitor_name' => 'Ahmed Belkacem', 'visitor_email' => 'ahmed.b@email.com', 'visitor_phone' => '06 67 89 01 23', 'date' => '2025-06-21', 'time' => '10:00', 'status' => 'confirme', 'notes' => null, 'created_at' => $now, 'updated_at' => $now],
            ['landlord_id' => 1, 'property_id' => 5, 'visitor_name' => 'Claire Moreau', 'visitor_email' => 'claire.moreau@email.com', 'visitor_phone' => '06 78 90 12 34', 'date' => '2025-06-22', 'time' => '16:00', 'status' => 'planifie', 'notes' => 'Souhaite ouvrir une boulangerie', 'created_at' => $now, 'updated_at' => $now],
            ['landlord_id' => 1, 'property_id' => 1, 'visitor_name' => 'Marc Lefevre', 'visitor_email' => 'marc.lefevre@email.com', 'visitor_phone' => '06 89 01 23 45', 'date' => '2025-06-15', 'time' => '11:00', 'status' => 'effectue', 'notes' => 'Visite faite, dossier à suivre', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
