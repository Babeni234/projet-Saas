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
        Schema::create('locataires', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->string('telephone')->nullable();
            $table->string('profil')->nullable(); // Image de profil
            $table->json('documentations')->nullable(); // Documents justificatifs
            $table->string('statut')->default('inactif'); // actif, inactif, suspendu, affecté
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
        Schema::dropIfExists('locataires');
    }
};
