<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiscal_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('year');
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('total_expenses', 12, 2)->default(0);
            $table->decimal('net_income', 12, 2)->default(0);
            $table->string('status')->default('draft'); // draft, ready, exported
            $table->json('data')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'year']);
        });

        Schema::create('fiscal_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiscal_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->string('category'); // interest, maintenance, management, insurance, tax, co_ownership, other
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->string('document_path')->nullable();
            $table->boolean('deductible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_expenses');
        Schema::dropIfExists('fiscal_years');
    }
};
