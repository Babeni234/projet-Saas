<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('conversations')->insert([
            ['user_id' => 1, 'subject' => 'Demande de visite — Maison Caluire', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 1, 'subject' => 'Question sur le T3 Lyon 2', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('conversation_participants')->insert([
            ['conversation_id' => 1, 'user_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['conversation_id' => 2, 'user_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('messages')->insert([
            ['conversation_id' => 1, 'user_id' => 1, 'content' => 'Bonjour, je suis intéressé par la maison avec jardin à Caluire. Est-elle toujours disponible ?', 'created_at' => $now, 'updated_at' => $now],
            ['conversation_id' => 1, 'user_id' => 1, 'content' => 'Oui elle est toujours disponible. Souhaitez-vous programmer une visite ?', 'created_at' => $now, 'updated_at' => $now],
            ['conversation_id' => 2, 'user_id' => 1, 'content' => 'Bonjour, les charges sont-elles incluses dans le loyer ?', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
