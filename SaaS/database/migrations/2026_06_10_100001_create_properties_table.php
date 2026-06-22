<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('property_type'); // apartment, house, studio, loft, villa
            $table->string('transaction_type'); // rent, sale, both
            $table->string('address');
            $table->string('city');
            $table->string('postal_code', 10)->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('surface', 10, 2)->nullable();
            $table->integer('rooms')->default(1);
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->boolean('furnished')->default(false);
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();
            $table->date('available_from')->nullable();
            $table->boolean('charges_included')->default(false);
            $table->decimal('deposit', 12, 2)->nullable();
            $table->integer('min_lease_duration')->nullable();
            $table->string('status')->default('draft'); // draft, active, rented, sold, pending
            $table->string('reference', 50)->nullable()->unique();
            $table->integer('views')->default(0);
            $table->integer('inquiries')->default(0);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
