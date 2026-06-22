<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('locale')->default('fr');
            $table->timestamp('last_login_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('public_visit_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('start_time');
            $table->string('end_time');
            $table->integer('max_visitors')->default(1);
            $table->integer('booked_count')->default(0);
            $table->string('status')->default('available'); // available, full, cancelled
            $table->timestamps();
        });

        Schema::create('public_visit_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_id')->constrained('public_visit_slots')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('visitor_name');
            $table->string('visitor_email');
            $table->string('visitor_phone');
            $table->text('message')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, cancelled
            $table->string('confirmation_token', 64)->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_visit_bookings');
        Schema::dropIfExists('public_visit_slots');
        Schema::dropIfExists('tenant_users');
    }
};
