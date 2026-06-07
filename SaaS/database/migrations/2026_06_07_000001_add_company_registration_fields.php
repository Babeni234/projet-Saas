<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_type')->default('individual')->after('email');
        });

        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('business_type');
            $table->string('legal_name');
            $table->string('registration_number');
            $table->string('tax_id');
            $table->string('country', 2);
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('legal_representative_name');
            $table->string('legal_representative_id_number');
            $table->string('phone');
            $table->string('verification_status')->default('pending');
            $table->timestamps();
        });

        Schema::create('company_legal_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profile_id')->constrained()->cascadeOnDelete();
            $table->string('document_type');
            $table->string('disk')->default('local');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->string('ai_status')->default('pending');
            $table->json('ai_extracted_data')->nullable();
            $table->timestamp('ai_processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_legal_documents');
        Schema::dropIfExists('company_profiles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('account_type');
        });
    }
};
