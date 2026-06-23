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
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->unsignedBigInteger('company_profile_id');
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->string('nom');
            $table->string('type'); // Financier, Occupation, Maintenance, Loyer, Personnalisé
            $table->string('periode'); // e.g. "Juin 2026"
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->text('ai_analysis')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->boolean('deleted')->default(false);
            $table->timestamps();
            $table->softDeletes(); // optional but good practice

            // Foreign keys
            $table->foreign('company_profile_id')->references('id')->on('company_profiles')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
