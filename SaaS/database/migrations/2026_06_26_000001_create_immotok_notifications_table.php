<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('immotok_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immotok_client_id')->constrained('immotok_clients')->onDelete('cascade');
            $table->foreignId('company_profile_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
            $table->unsignedBigInteger('illustration_id')->nullable();
            $table->string('type', 50)->default('publication');
            $table->string('title', 255);
            $table->text('message')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immotok_notifications');
    }
};
