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
        Schema::table('affectations', function (Blueprint $table) {
            $table->foreignId('type_contrat_id')
                  ->after('logement_id')
                  ->nullable()
                  ->constrained('type_contrats')
                  ->onDelete('set null');
            $table->string('type_bail')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affectations', function (Blueprint $table) {
            $table->dropForeign(['type_contrat_id']);
            $table->dropColumn('type_contrat_id');
            $table->string('type_bail')->nullable(false)->change();
        });
    }
};
