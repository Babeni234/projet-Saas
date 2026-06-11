<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    protected array $tables = [
        'users',
        'company_profiles',
        'company_legal_documents',
        'roles',
        'employees',
        'agencies'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add columns as nullable first
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                if (!Schema::hasColumn($table, 'uuid')) {
                    $tableBlueprint->uuid('uuid')->nullable();
                }
                if (!Schema::hasColumn($table, 'synced')) {
                    $tableBlueprint->boolean('synced')->default(0);
                }
                if ($table === 'employees' && !Schema::hasColumn($table, 'matricule')) {
                    $tableBlueprint->string('matricule')->nullable();
                }
            });
        }

        // 2. Backfill existing records with UUIDs and matricules
        foreach ($this->tables as $table) {
            DB::table($table)->get()->each(function ($record) use ($table) {
                $updates = [];
                if (empty($record->uuid)) {
                    $updates['uuid'] = (string) Str::uuid();
                }
                if ($table === 'employees' && empty($record->matricule)) {
                    $updates['matricule'] = 'EMP-' . str_pad($record->id, 5, '0', STR_PAD_LEFT);
                }
                
                if (!empty($updates)) {
                    DB::table($table)->where('id', $record->id)->update($updates);
                }
            });
        }

        // 3. Alter columns to be NOT NULL and add unique constraints
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                $tableBlueprint->uuid('uuid')->nullable(false)->change();
                $tableBlueprint->unique('uuid');
                
                if ($table === 'employees') {
                    $tableBlueprint->string('matricule')->nullable(false)->change();
                    $tableBlueprint->unique('matricule');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                if (Schema::hasColumn($table, 'uuid')) {
                    $tableBlueprint->dropUnique([ 'uuid' ]);
                    $tableBlueprint->dropColumn('uuid');
                }
                if (Schema::hasColumn($table, 'synced')) {
                    $tableBlueprint->dropColumn('synced');
                }
                if ($table === 'employees' && Schema::hasColumn($table, 'matricule')) {
                    $tableBlueprint->dropUnique([ 'matricule' ]);
                    $tableBlueprint->dropColumn('matricule');
                }
            });
        }
    }
};
