<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_pro')->default(false);
            $table->string('company_name')->nullable();
            $table->string('siret')->nullable();
            $table->string('phone_pro')->nullable();
            $table->string('address_pro')->nullable();
            $table->string('logo_path')->nullable();
            $table->json('pro_settings')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_pro', 'company_name', 'siret', 'phone_pro', 'address_pro', 'logo_path', 'pro_settings']);
        });
    }
};
