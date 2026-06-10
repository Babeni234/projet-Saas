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
        Schema::table('roles', function (Blueprint $table) {
            // Drop the old global unique slug constraint
            $table->dropUnique('roles_slug_unique');

            // Add new compound unique constraint on company_profile_id and slug
            $table->unique(['company_profile_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['company_profile_id', 'slug']);
            $table->unique('slug');
        });
    }
};
