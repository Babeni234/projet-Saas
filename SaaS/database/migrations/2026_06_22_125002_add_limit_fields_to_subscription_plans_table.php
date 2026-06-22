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
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->integer('max_logements')->default(-1)->after('price');
            $table->integer('max_locataires')->default(-1)->after('max_logements');
            $table->integer('max_employees')->default(-1)->after('max_locataires');
            $table->integer('max_agencies')->default(-1)->after('max_employees');
            $table->integer('max_buildings')->default(-1)->after('max_agencies');
            $table->boolean('has_ai')->default(true)->after('max_buildings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn([
                'max_logements',
                'max_locataires',
                'max_employees',
                'max_agencies',
                'max_buildings',
                'has_ai'
            ]);
        });
    }
};
