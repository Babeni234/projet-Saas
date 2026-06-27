<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $companyProfiles = DB::table('company_profiles')->get();
        foreach ($companyProfiles as $profile) {
            $exists = DB::table('roles')
                ->where('company_profile_id', $profile->id)
                ->where('slug', 'gestionnaire_immo')
                ->exists();

            if (!$exists) {
                $now = now();
                DB::table('roles')->insert([
                    'uuid' => (string) Str::uuid(),
                    'name' => 'Gestionnaire Immo',
                    'slug' => 'gestionnaire_immo',
                    'description' => 'Gestion immobilière opérationnelle (Bâtiments, Logements, Contrats, Locataires, Renouvellements, Engagements, États des lieux).',
                    'company_profile_id' => $profile->id,
                    'permissions' => json_encode([
                        'locataires.view', 'locataires.create', 'locataires.edit',
                        'batiments.view', 'batiments.create', 'batiments.edit',
                        'logements.view', 'logements.create', 'logements.edit',
                        'contrats.view', 'contrats.create', 'contrats.edit',
                        'renouvellements.view', 'renouvellements.create', 'renouvellements.edit',
                        'engagements.view', 'engagements.create', 'engagements.edit',
                        'etats_lieux.view', 'etats_lieux.create', 'etats_lieux.edit'
                    ]),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->where('slug', 'gestionnaire_immo')->delete();
    }
};
