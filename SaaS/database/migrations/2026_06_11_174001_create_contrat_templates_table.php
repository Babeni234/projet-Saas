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
        Schema::create('contrat_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('type_contrat_id')->constrained('type_contrats')->onDelete('cascade');
            $table->longText('content')->nullable(); // HTML content template
            $table->boolean('deleted')->default(0);
            $table->timestamps();

            // Constraint: one template per company per lease/contract type
            $table->unique(['company_profile_id', 'type_contrat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrat_templates');
    }
};
