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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('account_type'); // company or individual
            $table->double('price');
            $table->string('billing_cycle')->default('monthly'); // monthly or yearly
            $table->json('features')->nullable();
            $table->boolean('popular')->default(false);
            $table->string('color')->default('from-indigo-500 to-indigo-650');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
