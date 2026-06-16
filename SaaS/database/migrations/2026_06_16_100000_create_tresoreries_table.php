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
        Schema::create('tresoreries', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->unsignedBigInteger('company_profile_id');
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->decimal('montant', 15, 2);
            $table->date('date_transaction');
            $table->string('source_type');
            $table->unsignedBigInteger('source_id');
            $table->string('motif');
            $table->boolean('deleted')->default(false);
            $table->softDeletes(); // deleted_at
            $table->timestamps();

            $table->foreign('company_profile_id')->references('id')->on('company_profiles')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
            $table->index(['source_type', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tresoreries');
    }
};
