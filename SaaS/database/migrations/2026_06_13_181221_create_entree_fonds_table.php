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
        Schema::create('entree_fonds', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('agency_id')->nullable()->constrained()->nullOnDelete();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->decimal('montant', 15, 2);
            $table->date('date_entree');
            $table->string('categorie')->nullable();
            $table->string('reference')->nullable();
            $table->string('statut')->default('En attente'); // Encaissé, En attente, Annulé
            $table->boolean('deleted')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entree_fonds');
    }
};
