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
        Schema::create('immotok_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immotok_client_id')->constrained('immotok_clients')->onDelete('cascade');
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['immotok_client_id', 'company_profile_id'], 'client_company_sub_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immotok_subscriptions');
    }
};
