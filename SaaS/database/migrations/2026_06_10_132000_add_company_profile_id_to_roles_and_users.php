<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Clean up old global roles to avoid conflicts
        DB::table('roles')->delete();

        // 2. Add company_profile_id column to roles and users
        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('company_profile_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_profile_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
        });

        // 3. Seed roles for all existing companies and assign users to their company profiles
        $companyProfiles = DB::table('company_profiles')->get();
        foreach ($companyProfiles as $profile) {
            $now = now();
            DB::table('roles')->insert([
                [
                    'name' => 'Admin',
                    'slug' => 'admin',
                    'description' => 'Administrateur avec accès complet à toutes les fonctionnalités.',
                    'permissions' => json_encode(['*']),
                    'company_profile_id' => $profile->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => "Chef d'agence",
                    'slug' => 'chef_agence',
                    'description' => "Gestion et supervision d'une agence spécifique.",
                    'permissions' => json_encode(['manage_agencies', 'manage_properties', 'view_reports']),
                    'company_profile_id' => $profile->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Gestionnaire',
                    'slug' => 'gestionnaire',
                    'description' => 'Gestion des opérations quotidiennes et des locations.',
                    'company_profile_id' => $profile->id,
                    'permissions' => json_encode(['manage_properties', 'view_reports']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Comptable',
                    'slug' => 'comptable',
                    'description' => 'Accès et gestion de la comptabilité et des factures.',
                    'company_profile_id' => $profile->id,
                    'permissions' => json_encode(['manage_accounting', 'view_reports']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Maintenancier',
                    'slug' => 'maintenancier',
                    'description' => 'Gestion des tickets et interventions de maintenance.',
                    'company_profile_id' => $profile->id,
                    'permissions' => json_encode(['manage_maintenance']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Employé simple',
                    'slug' => 'employer_simple',
                    'description' => 'Employé simple avec accès de base.',
                    'company_profile_id' => $profile->id,
                    'permissions' => json_encode(['basic_access']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            // Assign the company owner user to their company's admin role
            $adminRole = DB::table('roles')
                ->where('company_profile_id', $profile->id)
                ->where('slug', 'admin')
                ->first();

            if ($adminRole) {
                DB::table('users')
                    ->where('id', $profile->user_id)
                    ->update([
                        'company_profile_id' => $profile->id,
                        'role_id' => $adminRole->id
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_profile_id']);
            $table->dropColumn('company_profile_id');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['company_profile_id']);
            $table->dropColumn('company_profile_id');
        });
    }
};
