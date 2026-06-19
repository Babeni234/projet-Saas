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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->foreignId('locataire_id')->unique()->constrained('locataires')->onDelete('cascade');
            $table->decimal('solde', 12, 2)->default(0.00);
            $table->string('password')->nullable(); // hashed wallet PIN/password
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('transacwallet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->string('type'); // 'debit', 'credit', 'recharge', 'virement'
            $table->decimal('amount', 12, 2);
            $table->string('description');
            $table->string('reference_tx')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacwallet');
        Schema::dropIfExists('wallets');
    }
};
