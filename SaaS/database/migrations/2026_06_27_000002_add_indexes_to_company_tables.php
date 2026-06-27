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
        $tables = [
            'batiments', 'locataires', 'contrats', 'depenses', 'entree_fonds', 
            'evenements', 'factures', 'frais_contrats', 'maintenances', 
            'proprietaires', 'rapports', 'tresoreries', 'wallets'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'company_profile_id')) {
                // Ensure we don't try to add a duplicate index if already present
                try {
                    Schema::table($tableName, function (Blueprint $table) {
                        $table->index('company_profile_id');
                    });
                } catch (\Exception $e) {
                    // Index already exists (e.g. via foreign key constraint), safe to ignore
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'batiments', 'locataires', 'contrats', 'depenses', 'entree_fonds', 
            'evenements', 'factures', 'frais_contrats', 'maintenances', 
            'proprietaires', 'rapports', 'tresoreries', 'wallets'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'company_profile_id')) {
                try {
                    Schema::table($tableName, function (Blueprint $table) {
                        $table->dropIndex(['company_profile_id']);
                    });
                } catch (\Exception $e) {
                    // Safe to ignore
                }
            }
        }
    }
};
