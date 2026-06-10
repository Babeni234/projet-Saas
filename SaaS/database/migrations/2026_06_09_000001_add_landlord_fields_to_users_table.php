<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('landlord_verified')->default(false);
            $table->string('landlord_verification_status')->nullable();
            $table->json('verification_documents')->nullable();
            $table->text('additional_info')->nullable();
            $table->timestamp('verified_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'landlord_verified',
                'landlord_verification_status',
                'verification_documents',
                'additional_info',
                'verified_at',
            ]);
        });
    }
};
