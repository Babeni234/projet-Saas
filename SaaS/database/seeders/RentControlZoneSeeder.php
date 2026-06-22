<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentControlZoneSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rent_control_zones')->insert([
            ['city' => 'Lyon', 'zone' => '1', 'max_price_per_sqm' => 20.0, 'reference_rent' => 17.5, 'reference_rent_plus' => 20.0, 'reference_rent_minus' => 15.0],
            ['city' => 'Lyon', 'zone' => '2', 'max_price_per_sqm' => 18.0, 'reference_rent' => 15.5, 'reference_rent_plus' => 18.0, 'reference_rent_minus' => 13.5],
            ['city' => 'Villeurbanne', 'zone' => '2', 'max_price_per_sqm' => 16.5, 'reference_rent' => 14.0, 'reference_rent_plus' => 16.5, 'reference_rent_minus' => 12.0],
            ['city' => 'Caluire-et-Cuire', 'zone' => '3', 'max_price_per_sqm' => 15.0, 'reference_rent' => 13.0, 'reference_rent_plus' => 15.0, 'reference_rent_minus' => 11.0],
        ]);

        DB::table('rent_control_compliance')->insert([
            ['property_id' => 1, 'zone_id' => 1, 'current_rent_per_sqm' => 14.62, 'max_allowed_rent_per_sqm' => 20.0, 'is_compliant' => true, 'excess_amount' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['property_id' => 2, 'zone_id' => 3, 'current_rent_per_sqm' => 23.64, 'max_allowed_rent_per_sqm' => 16.5, 'is_compliant' => false, 'excess_amount' => 7.14, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
