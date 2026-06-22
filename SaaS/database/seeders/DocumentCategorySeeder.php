<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('document_categories')->insert([
            ['name' => 'Pièce d\'identité', 'slug' => 'piece-identite', 'type' => 'locataire', 'required' => true],
            ['name' => 'Justificatif de domicile', 'slug' => 'justificatif-domicile', 'type' => 'locataire', 'required' => true],
            ['name' => 'Contrat de bail', 'slug' => 'contrat-bail', 'type' => 'bien', 'required' => true],
            ['name' => 'Diagnostic technique', 'slug' => 'diagnostic-technique', 'type' => 'bien', 'required' => true],
            ['name' => 'Quittance de loyer', 'slug' => 'quittance-loyer', 'type' => 'locataire', 'required' => false],
            ['name' => 'Assurance habitation', 'slug' => 'assurance-habitation', 'type' => 'locataire', 'required' => true],
            ['name' => 'Etat des lieux', 'slug' => 'etat-lieux', 'type' => 'bien', 'required' => true],
            ['name' => 'Fiche technique', 'slug' => 'fiche-technique', 'type' => 'bien', 'required' => false],
        ]);
    }
}
