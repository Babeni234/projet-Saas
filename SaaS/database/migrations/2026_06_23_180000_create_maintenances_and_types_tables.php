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
        // 1. Create type_maintenances table
        Schema::create('type_maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('company_profile_id');
            $table->timestamps();

            $table->foreign('company_profile_id')
                ->references('id')
                ->on('company_profiles')
                ->onDelete('cascade');
        });

        // 2. Create maintenances table
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('company_profile_id');
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->unsignedBigInteger('type_maintenance_id');
            $table->text('description');
            $table->enum('priorite', ['Basse', 'Normale', 'Haute', 'Critique']);
            $table->decimal('budget', 12, 2)->default(0.00);
            $table->enum('statut', ['Créé', 'En cours', 'Terminé'])->default('Créé');
            $table->unsignedBigInteger('assigned_to')->nullable(); // references users.id (employee)
            
            // Polymorphic target (Logement or Batiment)
            $table->string('maintenanceable_type');
            $table->unsignedBigInteger('maintenanceable_id');
            
            $table->dateTime('date_debut_execution')->nullable();
            $table->dateTime('date_fin_execution')->nullable();
            $table->integer('duree_execution_minutes')->nullable();
            $table->timestamps();

            $table->foreign('company_profile_id')
                ->references('id')
                ->on('company_profiles')
                ->onDelete('cascade');

            $table->foreign('agency_id')
                ->references('id')
                ->on('agencies')
                ->onDelete('set null');

            $table->foreign('type_maintenance_id')
                ->references('id')
                ->on('type_maintenances')
                ->onDelete('cascade');

            $table->foreign('assigned_to')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        // 3. Add statut_maintenance to batiments and logements
        Schema::table('batiments', function (Blueprint $table) {
            $table->string('statut_maintenance')->default('Sain');
        });

        Schema::table('logements', function (Blueprint $table) {
            $table->string('statut_maintenance')->default('Sain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logements', function (Blueprint $table) {
            $table->dropColumn('statut_maintenance');
        });

        Schema::table('batiments', function (Blueprint $table) {
            $table->dropColumn('statut_maintenance');
        });

        Schema::dropIfExists('maintenances');
        Schema::dropIfExists('type_maintenances');
    }
};
