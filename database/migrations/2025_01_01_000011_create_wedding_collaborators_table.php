<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email');
            $table->string('role', 30)->default('viewer');
            $table->string('token', 64)->unique();
            $table->string('status', 20)->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->index('wedding_id');
            $table->index('user_id');
            $table->index('token');
            $table->index('email');
        });

        Schema::create('share_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->string('type', 30)->default('checklist');
            $table->string('permissions', 20)->default('read');
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('wedding_id');
            $table->index('token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('share_links');
        Schema::dropIfExists('wedding_collaborators');
    }
};
