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
        Schema::create('batiments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->boolean('synced')->default(0);

            // --- Affectations ---
            // La company est obligatoire (propriétaire de la donnée)
            $table->foreignId('company_profile_id')
                  ->nullable()
                  ->constrained('company_profiles')
                  ->onDelete('set null');

            // L'agence est optionnelle (bâtiment géré par une agence)
            $table->foreignId('agency_id')
                  ->nullable()
                  ->constrained('agencies')
                  ->onDelete('set null');

            // Le propriétaire est optionnel (lié à la table proprietaires)
            $table->foreignId('proprietaire_id')
                  ->nullable()
                  ->constrained('proprietaires')
                  ->onDelete('set null');

            // --- Identification ---
            $table->string('nom');                          // Nom du bâtiment
            $table->string('reference')->nullable();        // Référence interne (BAT-001)

            // --- Localisation ---
            $table->string('pays')->nullable();
            $table->string('ville')->nullable();
            $table->string('quartier')->nullable();
            $table->text('adresse')->nullable();
            $table->string('code_postal')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // --- Capacité ---
            $table->integer('etages')->default(0);
            $table->integer('appartements')->default(0);   // Nombre d'unités / logements
            $table->decimal('surface_totale', 10, 2)->nullable(); // Surface en m²

            // --- Caractéristiques ---
            $table->integer('annee_construction')->nullable();
            $table->string('type_batiment')->nullable();
            // Ex: 'Résidentiel', 'Commercial', 'Mixte', 'Industriel'
            $table->string('type_structure')->nullable();
            // Ex: 'Béton', 'Bois', 'Métal', 'Mixte'
            $table->integer('nombre_parkings')->nullable()->default(0);
            $table->boolean('ascenseur')->default(false);
            $table->boolean('gardiennage')->default(false);
            $table->boolean('piscine')->default(false);
            $table->boolean('generatrice')->default(false);

            // --- Statut opérationnel ---
            $table->string('statut')->default('Actif');
            // Valeurs: 'Actif' | 'Maintenance' | 'Fermé' | 'En construction'

            // --- Documents & Notes ---
            $table->text('description')->nullable();
            $table->text('notes_internes')->nullable();
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
        Schema::dropIfExists('batiments');
    }
};
