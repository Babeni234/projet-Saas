<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Workflow definitions
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('trigger_type')->default('manual'); // manual, scheduled, receipt_overdue, contract_ending, visit_reminder, incident_reported, rent_due
            $table->json('trigger_config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Workflow steps
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('step_type'); // action, condition, delay, approval, notification, webhook
            $table->json('config')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 3. Step connections (directed edges)
        Schema::create('workflow_step_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_step_id')->constrained('workflow_steps')->cascadeOnDelete();
            $table->foreignId('to_step_id')->constrained('workflow_steps')->cascadeOnDelete();
            $table->string('condition_label')->nullable(); // null=default, 'yes'/'no' for conditions
            $table->unique(['from_step_id', 'to_step_id']);
        });

        // 4. Workflow instances (running/completed)
        Schema::create('workflow_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('running'); // running, completed, failed, cancelled
            $table->foreignId('current_step_id')->nullable()->constrained('workflow_steps')->nullOnDelete();
            $table->json('context')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // 5. Instance step logs
        Schema::create('workflow_instance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workflow_step_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, running, completed, failed, skipped
            $table->json('output')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
        });

        // 6. Workflow templates
        Schema::create('workflow_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->default('general'); // general, financial, incident, visit, contract
            $table->json('config')->nullable(); // full workflow + steps definition
            $table->boolean('is_built_in')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_instance_logs');
        Schema::dropIfExists('workflow_instances');
        Schema::dropIfExists('workflow_step_connections');
        Schema::dropIfExists('workflow_steps');
        Schema::dropIfExists('workflows');
        Schema::dropIfExists('workflow_templates');
    }
};
