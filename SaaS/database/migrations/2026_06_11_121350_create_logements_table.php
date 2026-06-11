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
        Schema::create('logements', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference')->unique();
            
            $table->foreignId('company_profile_id')
                  ->constrained('company_profiles')
                  ->onDelete('cascade');

            $table->foreignId('agency_id')
                  ->nullable()
                  ->constrained('agencies')
                  ->onDelete('set null');

            $table->foreignId('batiment_id')
                  ->nullable()
                  ->constrained('batiments')
                  ->onDelete('set null');

            $table->foreignId('categorie_id')
                  ->constrained('categories')
                  ->onDelete('cascade');

            $table->integer('etage')->default(0);
            $table->integer('surface')->nullable();
            $table->decimal('loyer', 12, 2)->nullable();
            $table->string('statut')->default('Libre'); // Libre, Occupé, Réservé
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
        Schema::dropIfExists('logements');
    }
};
