<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add company_profile_id to agencies table
        Schema::table('agencies', function (Blueprint $table) {
            $table->foreignId('company_profile_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
        });

        // Link existing agencies to the first company profile if it exists
        $firstCompany = DB::table('company_profiles')->first();
        if ($firstCompany) {
            DB::table('agencies')->update(['company_profile_id' => $firstCompany->id]);
        }

        // 2. Create employees table
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_profile_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('agency_id')->nullable()->constrained('agencies')->onDelete('set null');
            $table->string('phone')->nullable();
            $table->string('position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');

        Schema::table('agencies', function (Blueprint $table) {
            $table->dropForeign(['company_profile_id']);
            $table->dropColumn('company_profile_id');
        });
    }
};
