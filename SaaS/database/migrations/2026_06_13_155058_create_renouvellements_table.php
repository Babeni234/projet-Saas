<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('renouvellements', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference')->unique();
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->foreignId('contrat_id')->constrained('contrats')->onDelete('cascade');
            $table->decimal('nouveau_loyer', 12, 2);
            $table->string('cycle_paiement');
            $table->string('duree');
            $table->decimal('frais_contrat', 12, 2)->default(0);
            $table->text('motif_demande')->nullable();
            $table->text('motif_rejet')->nullable();
            $table->string('statut')->default('En attente'); // En attente, A venir, Complete, Rejeté
            $table->boolean('deleted')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renouvellements');
    }
};
