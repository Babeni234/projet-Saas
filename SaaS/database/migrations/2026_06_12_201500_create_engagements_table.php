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
        Schema::create('engagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->foreignId('batiment_id')->nullable()->constrained('batiments')->onDelete('set null');
            $table->foreignId('locataire_id')->nullable()->constrained('locataires')->onDelete('set null');
            $table->foreignId('contrat_id')->nullable()->constrained('contrats')->onDelete('set null');
            $table->foreignId('type_engagement_id')->constrained('type_engagements')->onDelete('cascade');
            $table->string('reference');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('montant', 15, 2)->nullable();
            $table->string('statut')->default('actif'); // actif, en cours, expirer, valider
            $table->string('statut_honneur')->nullable(); // honner, non honner
            $table->text('instructions')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagements');
    }
};
