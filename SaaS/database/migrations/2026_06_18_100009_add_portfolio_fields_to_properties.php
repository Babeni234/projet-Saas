<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('estimated_value', 12, 2)->nullable();
            $table->decimal('monthly_charges', 10, 2)->nullable();
            $table->decimal('tax_annual', 10, 2)->nullable();
            $table->decimal('loan_payment', 10, 2)->nullable();
            $table->decimal('property_tax', 10, 2)->nullable();
            $table->decimal('co_ownership_fees', 10, 2)->nullable();
            $table->decimal('insurance_annual', 10, 2)->nullable();
            $table->decimal('management_fees', 10, 2)->nullable();
            $table->string('energy_class')->nullable();
            $table->string('ges_class')->nullable();
            $table->year('construction_year')->nullable();
            $table->decimal('lot_size', 10, 2)->nullable();
            $table->integer('floor')->nullable();
            $table->boolean('has_elevator')->default(false);
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_balcony')->default(false);
            $table->boolean('has_garden')->default(false);
            $table->boolean('has_pool')->default(false);
            $table->boolean('has_cellar')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'purchase_price', 'purchase_date', 'estimated_value', 'monthly_charges',
                'tax_annual', 'loan_payment', 'property_tax', 'co_ownership_fees',
                'insurance_annual', 'management_fees', 'energy_class', 'ges_class',
                'construction_year', 'lot_size', 'floor', 'has_elevator', 'has_parking',
                'has_balcony', 'has_garden', 'has_pool', 'has_cellar',
            ]);
        });
    }
};
