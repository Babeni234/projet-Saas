<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ajoute la colonne affectation_id dans la table contrats
     * et fait la jointure entre affectations et contrats.
     */
    public function up(): void
    {
        // 1. Ajouter la colonne affectation_id
        Schema::table('contrats', function (Blueprint $table) {
            $table->unsignedBigInteger('affectation_id')->nullable()->after('agency_id');
            $table->foreign('affectation_id')
                  ->references('id')
                  ->on('affectations')
                  ->onDelete('set null');
        });

        // 2. Peupler affectation_id pour les contrats existants
        //    On retrouve l'affectation via (locataire_id + logement_id)
        $contrats = DB::table('contrats')->whereNull('deleted_at')->get();

        foreach ($contrats as $contrat) {
            $affectation = DB::table('affectations')
                ->where('locataire_id', $contrat->locataire_id)
                ->where('logement_id', $contrat->logement_id)
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->first();

            if ($affectation) {
                DB::table('contrats')
                    ->where('id', $contrat->id)
                    ->update(['affectation_id' => $affectation->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrats', function (Blueprint $table) {
            $table->dropForeign(['affectation_id']);
            $table->dropColumn('affectation_id');
        });
    }
};
