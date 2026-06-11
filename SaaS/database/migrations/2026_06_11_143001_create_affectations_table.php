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
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference')->unique();
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->foreignId('logement_id')->constrained('logements')->onDelete('cascade');
            $table->decimal('loyer', 12, 2);
            $table->decimal('caution', 12, 2);
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->string('type_bail');
            $table->string('duree');
            $table->string('cycle_paiement');
            $table->string('statut')->default('Actif'); // Actif, Terminé, À venir
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
        Schema::dropIfExists('affectations');
    }
};
