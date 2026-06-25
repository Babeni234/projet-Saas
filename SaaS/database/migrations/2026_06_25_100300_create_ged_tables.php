<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('document_folders')->cascadeOnDelete();
            $table->string('name');
            $table->string('color', 7)->default('#6366f1');
            $table->string('icon')->nullable();
            $table->boolean('is_shared')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('document_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('permission')->default('view'); // view, download
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // bail, avenant, etat_lieux, quittance, other
            $table->text('description')->nullable();
            $table->json('content');
            $table->string('color', 7)->default('#6366f1');
            $table->boolean('is_built_in')->default(false);
            $table->timestamps();
        });

        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('file_path');
            $table->unsignedInteger('file_size')->nullable();
            $table->unsignedInteger('version_number');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('folder_id')->nullable()->after('document_category_id')->constrained('document_folders')->nullOnDelete();
            $table->boolean('is_starred')->default(false)->after('folder_id');
            $table->string('tags')->nullable()->after('is_starred');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['folder_id']);
            $table->dropColumn(['folder_id', 'is_starred', 'tags']);
        });

        Schema::dropIfExists('document_versions');
        Schema::dropIfExists('document_templates');
        Schema::dropIfExists('document_shares');
        Schema::dropIfExists('document_folders');
    }
};
