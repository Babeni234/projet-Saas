<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landlord_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('lease_type');
            $table->string('duration');
            $table->decimal('rent', 10, 2);
            $table->decimal('charges', 10, 2)->default(0);
            $table->decimal('deposit', 10, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('pending'); // active, pending, expired, terminated
            $table->boolean('deposit_paid')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
