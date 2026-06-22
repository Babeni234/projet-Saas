<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rent_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('landlord_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('previous_rent', 10, 2);
            $table->decimal('new_rent', 10, 2);
            $table->decimal('percentage_change', 6, 3);
            $table->string('index_name')->default('IRL'); // IRL, ICC, etc
            $table->decimal('index_value_old', 10, 3);
            $table->decimal('index_value_new', 10, 3);
            $table->date('effective_date');
            $table->string('status')->default('pending'); // pending, applied, cancelled
            $table->string('pdf_path')->nullable();
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_revisions');
    }
};
