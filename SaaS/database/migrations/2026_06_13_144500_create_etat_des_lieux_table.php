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
        Schema::create('etat_des_lieux', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->foreignId('batiment_id')->nullable()->constrained('batiments')->onDelete('set null');
            $table->foreignId('logement_id')->constrained('logements')->onDelete('cascade');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->foreignId('contrat_id')->constrained('contrats')->onDelete('cascade');
            $table->foreignId('type_etat_des_lieux_id')->constrained('type_etat_des_lieux')->onDelete('cascade');
            $table->string('reference');
            $table->date('date');
            $table->string('statut')->default('Brouillon');
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
        Schema::dropIfExists('etat_des_lieux');
    }
};
