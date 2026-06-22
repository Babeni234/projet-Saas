<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicVisitSlotSeeder extends Seeder
{
    public function run(): void
    {
        $slots = [];
        $startDate = strtotime('+1 day');
        for ($i = 0; $i < 5; $i++) {
            $date = date('Y-m-d', strtotime("+$i day"));
            $slots[] = ['property_id' => 4, 'date' => $date, 'start_time' => '10:00', 'end_time' => '11:00', 'max_visitors' => 3, 'booked_count' => 0, 'status' => 'disponible', 'created_at' => now(), 'updated_at' => now()];
            $slots[] = ['property_id' => 4, 'date' => $date, 'start_time' => '14:00', 'end_time' => '15:00', 'max_visitors' => 3, 'booked_count' => 0, 'status' => 'disponible', 'created_at' => now(), 'updated_at' => now()];
            $slots[] = ['property_id' => 4, 'date' => $date, 'start_time' => '16:00', 'end_time' => '17:00', 'max_visitors' => 3, 'booked_count' => 0, 'status' => 'disponible', 'created_at' => now(), 'updated_at' => now()];
        }
        DB::table('public_visit_slots')->insert($slots);
    }
}
