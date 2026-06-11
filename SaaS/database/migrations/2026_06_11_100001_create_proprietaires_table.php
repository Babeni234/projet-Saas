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
        Schema::create('proprietaires', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->boolean('synced')->default(0);

            // Lien entreprise (propriétaire appartient à une entreprise)
            $table->foreignId('company_profile_id')
                  ->constrained('company_profiles')
                  ->onDelete('cascade');

            // --- Identité du propriétaire ---
            $table->string('type')->default('personne_physique');
            // Valeurs: 'personne_physique' | 'personne_morale'

            $table->string('nom');                         // Nom de famille ou raison sociale
            $table->string('prenom')->nullable();          // Prénom (personne physique)
            $table->string('raison_sociale')->nullable();  // Raison sociale (personne morale)
            $table->string('siret')->nullable();           // SIRET / Numéro d'immatriculation
            $table->string('numero_contribuable')->nullable(); // NIF / NUMÉRO CONTRIBUABLE

            // --- Pièce d'identité ---
            $table->string('piece_identite_type')->nullable();
            // Ex: 'CNI', 'Passeport', 'Titre de séjour', 'Carte professionnelle'
            $table->string('piece_identite_numero')->nullable();

            // --- Coordonnées ---
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('telephone_secondaire')->nullable();
            $table->text('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->nullable()->default('CM');
            $table->string('code_postal')->nullable();

            // --- Infos bancaires ---
            $table->string('banque')->nullable();
            $table->string('rib')->nullable();    // RIB / IBAN
            $table->string('swift')->nullable();  // BIC / SWIFT

            // --- Contrat avec l'entreprise ---
            $table->string('statut')->default('actif');
            // Valeurs: 'actif' | 'inactif' | 'suspendu'
            $table->date('date_debut_contrat')->nullable();
            $table->date('date_fin_contrat')->nullable();
            $table->decimal('commission_taux', 5, 2)->nullable();
            // Taux de commission en % (ex: 8.50 = 8.5%)
            $table->string('type_contrat')->nullable();
            // Ex: 'gestion_locative', 'syndic', 'mandat_vente'

            // --- Informations complémentaires ---
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();

            // --- Soft delete & sync ---
            $table->boolean('deleted')->default(0);
            $table->softDeletes(); // deleted_at

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proprietaires');
    }
};
