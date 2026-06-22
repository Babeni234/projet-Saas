<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rent_control_zones', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('zone'); // zone_1, zone_2, zone_3, etc
            $table->decimal('max_price_per_sqm', 8, 2)->nullable();
            $table->decimal('reference_rent', 8, 2)->nullable();
            $table->decimal('reference_rent_plus', 8, 2)->nullable();
            $table->decimal('reference_rent_minus', 8, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('rent_control_compliance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('rent_control_zones')->nullOnDelete();
            $table->decimal('current_rent_per_sqm', 8, 2);
            $table->decimal('max_allowed_rent_per_sqm', 8, 2)->nullable();
            $table->boolean('is_compliant')->default(true);
            $table->decimal('excess_amount', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rent_control_compliance');
        Schema::dropIfExists('rent_control_zones');
    }
};
