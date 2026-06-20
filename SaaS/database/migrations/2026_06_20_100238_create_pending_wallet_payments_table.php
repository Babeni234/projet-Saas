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
        Schema::create('pending_wallet_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->foreignId('locataire_id')->constrained('locataires')->onDelete('cascade');
            $table->string('type'); // 'loyer' or 'facture'
            $table->unsignedBigInteger('target_id')->nullable(); // e.g. invoice_id or contract_id
            $table->decimal('amount', 12, 2);
            $table->string('token')->unique();
            $table->json('data'); // stores original request payload parameters
            $table->string('status')->default('pending'); // 'pending', 'validated', 'expired', 'cancelled'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_wallet_payments');
    }
};
