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
        Schema::create('type_depenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });

        Schema::table('depenses', function (Blueprint $table) {
            $table->foreignId('type_depense_id')->nullable()->constrained('type_depenses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('depenses', function (Blueprint $table) {
            $table->dropForeign(['type_depense_id']);
            $table->dropColumn('type_depense_id');
        });

        Schema::dropIfExists('type_depenses');
    }
};
