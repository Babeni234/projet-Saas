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
        // 1. Clients
        Schema::create('immotok_clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        // 2. Likes
        Schema::create('immotok_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immotok_client_id')->constrained('immotok_clients')->onDelete('cascade');
            $table->foreignId('illustration_id')->constrained('illustrations')->onDelete('cascade');
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['immotok_client_id', 'illustration_id'], 'client_illustration_like_unique');
        });

        // 3. Favorites
        Schema::create('immotok_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immotok_client_id')->constrained('immotok_clients')->onDelete('cascade');
            $table->foreignId('illustration_id')->constrained('illustrations')->onDelete('cascade');
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['immotok_client_id', 'illustration_id'], 'client_illustration_fav_unique');
        });

        // 4. Comments
        Schema::create('immotok_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immotok_client_id')->nullable()->constrained('immotok_clients')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('illustration_id')->constrained('illustrations')->onDelete('cascade');
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('text');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('immotok_comments')->onDelete('cascade');
        });

        // 5. Messages
        Schema::create('immotok_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immotok_client_id')->constrained('immotok_clients')->onDelete('cascade');
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->enum('sender', ['client', 'company', 'agency']);
            $table->text('message');
            $table->boolean('is_ai_reply')->default(false);
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 6. Add AI settings to company_profiles and agencies
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->boolean('immotok_ai_enabled')->default(false);
        });

        Schema::table('agencies', function (Blueprint $table) {
            $table->boolean('immotok_ai_enabled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn('immotok_ai_enabled');
        });

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropColumn('immotok_ai_enabled');
        });

        Schema::dropIfExists('immotok_messages');
        Schema::dropIfExists('immotok_comments');
        Schema::dropIfExists('immotok_favorites');
        Schema::dropIfExists('immotok_likes');
        Schema::dropIfExists('immotok_clients');
    }
};
