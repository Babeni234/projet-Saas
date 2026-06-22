<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;
        $now = now();

        DB::table('properties')->insert([
            ['user_id' => $userId, 'title' => 'T3 Lumineux — Lyon 2', 'property_type' => 'apartment', 'transaction_type' => 'rent', 'address' => '12 rue de la Paix, 69002 Lyon', 'city' => 'Lyon', 'postal_code' => '69002', 'surface' => 65, 'rooms' => 3, 'bedrooms' => 2, 'price' => 950, 'deposit' => 950, 'status' => 'rented', 'description' => 'Bel appartement T3 entièrement rénové au cœur du 2ème arrondissement.', 'reference' => 'PROP-001', 'purchase_price' => 285000, 'purchase_date' => '2020-03-15', 'monthly_charges' => 80, 'energy_class' => 'C', 'ges_class' => 'B', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $userId, 'title' => 'Studio Meublé — Villeurbanne', 'property_type' => 'studio', 'transaction_type' => 'rent', 'address' => '5 avenue Jean Jaurès, 69100 Villeurbanne', 'city' => 'Villeurbanne', 'postal_code' => '69100', 'surface' => 22, 'rooms' => 1, 'bedrooms' => 1, 'price' => 520, 'deposit' => 520, 'status' => 'rented', 'description' => 'Studio meublé idéal pour étudiant.', 'reference' => 'PROP-002', 'purchase_price' => 85000, 'purchase_date' => '2019-09-01', 'monthly_charges' => 45, 'energy_class' => 'D', 'ges_class' => 'C', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $userId, 'title' => 'T2 — Vénissieux', 'property_type' => 'apartment', 'transaction_type' => 'rent', 'address' => '8 rue des Lilas, 69200 Vénissieux', 'city' => 'Vénissieux', 'postal_code' => '69200', 'surface' => 45, 'rooms' => 2, 'bedrooms' => 1, 'price' => 620, 'deposit' => 620, 'status' => 'rented', 'description' => 'Appartement T2 calme et bien situé.', 'reference' => 'PROP-003', 'purchase_price' => 120000, 'purchase_date' => '2021-06-01', 'monthly_charges' => 60, 'energy_class' => 'E', 'ges_class' => 'D', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $userId, 'title' => 'Maison avec Jardin — Caluire', 'property_type' => 'house', 'transaction_type' => 'both', 'address' => '12 chemin des Vignes, 69300 Caluire-et-Cuire', 'city' => 'Caluire-et-Cuire', 'postal_code' => '69300', 'surface' => 120, 'rooms' => 5, 'bedrooms' => 3, 'price' => 385000, 'deposit' => 1450, 'status' => 'active', 'description' => 'Magnifique maison de ville avec jardin.', 'reference' => 'PROP-004', 'purchase_price' => 350000, 'purchase_date' => '2022-01-15', 'monthly_charges' => 0, 'energy_class' => 'B', 'ges_class' => 'A', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $userId, 'title' => 'Local Commercial — Lyon 3', 'property_type' => 'apartment', 'transaction_type' => 'rent', 'address' => '45 rue de la République, 69003 Lyon', 'city' => 'Lyon', 'postal_code' => '69003', 'surface' => 80, 'rooms' => 1, 'bedrooms' => 0, 'price' => 2200, 'deposit' => 4400, 'status' => 'draft', 'description' => 'Local commercial vitrine sur rue.', 'reference' => 'PROP-005', 'purchase_price' => 400000, 'purchase_date' => '2023-05-01', 'monthly_charges' => 150, 'energy_class' => 'C', 'ges_class' => 'C', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
