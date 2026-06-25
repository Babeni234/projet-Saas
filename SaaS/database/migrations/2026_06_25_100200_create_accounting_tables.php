<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('bank_name')->nullable();
            $table->string('iban')->nullable();
            $table->string('bic')->nullable();
            $table->string('type')->default('checking'); // checking, savings, cash
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('color', 7)->default('#6366f1');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('accounting_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // income, expense
            $table->string('color', 7)->default('#6366f1');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bank_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('accounting_categories')->nullOnDelete();
            $table->string('type'); // income, expense, transfer
            $table->decimal('amount', 12, 2);
            $table->string('description')->nullable();
            $table->date('transaction_date');
            $table->string('status')->default('completed'); // pending, completed, cancelled
            $table->string('reference')->nullable();
            $table->string('payment_method')->nullable(); // bank_transfer, cash, card, check, online
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('receipt_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_reconciled')->default(false);
            $table->timestamp('reconciled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bank_account_id')->constrained()->cascadeOnDelete();
            $table->date('date_from');
            $table->date('date_to');
            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->decimal('closing_balance', 12, 2)->default(0);
            $table->decimal('statement_balance', 12, 2)->default(0);
            $table->decimal('difference', 12, 2)->default(0);
            $table->string('status')->default('pending'); // pending, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('accounting_categories')->nullOnDelete();
            $table->unsignedInteger('month');
            $table->unsignedInteger('year');
            $table->decimal('amount', 12, 2);
            $table->decimal('spent', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'category_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('reconciliations');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('accounting_categories');
        Schema::dropIfExists('bank_accounts');
    }
};
