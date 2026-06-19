<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ajoute affectation_id dans frais_contrats pour lier directement au bail initial.
     */
    public function up(): void
    {
        Schema::table('frais_contrats', function (Blueprint $table) {
            $table->unsignedBigInteger('affectation_id')->nullable()->after('agency_id');
            $table->foreign('affectation_id')
                  ->references('id')
                  ->on('affectations')
                  ->onDelete('set null');
        });

        // Peupler affectation_id pour les frais_contrats existants (via renouvellement → contrat → affectation)
        $frais = DB::table('frais_contrats')->whereNull('deleted_at')->get();
        foreach ($frais as $f) {
            if (!$f->renouvellement_id) continue;

            $renouvellement = DB::table('renouvellements')->where('id', $f->renouvellement_id)->first();
            if (!$renouvellement) continue;

            $contrat = DB::table('contrats')->where('id', $renouvellement->contrat_id)->first();
            if (!$contrat || !$contrat->affectation_id) continue;

            DB::table('frais_contrats')
                ->where('id', $f->id)
                ->update(['affectation_id' => $contrat->affectation_id]);
        }
    }

    public function down(): void
    {
        Schema::table('frais_contrats', function (Blueprint $table) {
            $table->dropForeign(['affectation_id']);
            $table->dropColumn('affectation_id');
        });
    }
};
