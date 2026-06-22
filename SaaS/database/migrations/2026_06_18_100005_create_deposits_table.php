<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->date('received_at');
            $table->date('returned_at')->nullable();
            $table->decimal('returned_amount', 10, 2)->nullable();
            $table->text('deduction_notes')->nullable();
            $table->json('deductions')->nullable();
            $table->string('status')->default('held'); // held, partially_returned, returned, disputed
            $table->string('bank_account')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
