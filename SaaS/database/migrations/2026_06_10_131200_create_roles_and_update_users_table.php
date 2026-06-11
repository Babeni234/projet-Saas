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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        // Insert initial roles
        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrateur avec accès complet à toutes les fonctionnalités.',
                'permissions' => json_encode(['*']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => "Chef d'agence",
                'slug' => 'chef_agence',
                'description' => "Gestion et supervision d'une agence spécifique.",
                'permissions' => json_encode(['manage_agencies', 'manage_properties', 'view_reports']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gestionnaire',
                'slug' => 'gestionnaire',
                'description' => 'Gestion des opérations quotidiennes et des locations.',
                'permissions' => json_encode(['manage_properties', 'view_reports']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Comptable',
                'slug' => 'comptable',
                'description' => 'Accès et gestion de la comptabilité et des factures.',
                'permissions' => json_encode(['manage_accounting', 'view_reports']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maintenancier',
                'slug' => 'maintenancier',
                'description' => 'Gestion des tickets et interventions de maintenance.',
                'permissions' => json_encode(['manage_maintenance']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employé simple',
                'slug' => 'employer_simple',
                'description' => 'Employé simple avec accès de base.',
                'permissions' => json_encode(['basic_access']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->string('status')->default('active'); // active, pending, inactive
            $table->timestamp('last_login_at')->nullable();
        });

        // Assign existing users to Admin role
        $adminRole = DB::table('roles')->where('slug', 'admin')->first();
        if ($adminRole) {
            DB::table('users')->update(['role_id' => $adminRole->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'status', 'last_login_at']);
        });

        Schema::dropIfExists('roles');
    }
};
