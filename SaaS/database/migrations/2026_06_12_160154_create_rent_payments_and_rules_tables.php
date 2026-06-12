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
        Schema::create('regle_loyers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->integer('jour_declenchement');
            $table->decimal('taux_penalite', 5, 2);
            $table->timestamps();
        });

        Schema::create('paiement_loyers', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->string('reference')->unique();
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->foreignId('contrat_id')->constrained('contrats')->onDelete('cascade');
            $table->date('date_reglement');
            $table->decimal('montant_total', 12, 2);
            $table->string('mode_reglement');
            $table->string('reference_tx')->nullable();
            $table->boolean('deleted')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mois_payes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paiement_loyer_id')->constrained('paiement_loyers')->onDelete('cascade');
            $table->string('periode');
            $table->decimal('loyer_de_base', 12, 2);
            $table->decimal('penalite', 12, 2);
            $table->decimal('total_paye', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mois_payes');
        Schema::dropIfExists('paiement_loyers');
        Schema::dropIfExists('regle_loyers');
    }
};
