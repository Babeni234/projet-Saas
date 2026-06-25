<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color')->default('#6366f1');
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('portfolio_property', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->unique(['portfolio_id', 'property_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_property');
        Schema::dropIfExists('portfolios');
    }
};
