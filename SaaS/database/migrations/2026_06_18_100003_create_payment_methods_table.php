<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // card, sepa, bank_transfer
            $table->string('provider'); // stripe, mango_pay
            $table->string('provider_id')->nullable();
            $table->string('last_four', 4)->nullable();
            $table->string('brand')->nullable();
            $table->string('iban_last_four', 4)->nullable();
            $table->string('bic')->nullable();
            $table->string('mandate_id')->nullable();
            $table->date('mandate_signed_at')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('active'); // active, paused, cancelled
            $table->decimal('amount', 10, 2);
            $table->string('frequency'); // monthly, quarterly, yearly
            $table->integer('day_of_month')->default(5);
            $table->date('next_payment_date');
            $table->date('last_payment_date')->nullable();
            $table->date('cancelled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('receipt_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider_transaction_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('EUR');
            $table->string('status'); // pending, succeeded, failed, refunded
            $table->string('failure_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('payment_methods');
    }
};
