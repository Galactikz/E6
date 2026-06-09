<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('color', 20)->default('#6B7280');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('wedding_id');
        });

        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('rsvp_status', 20)->default('pending');
            $table->string('meal_type', 20)->default('adult');
            $table->string('dietary_requirements')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('invitation_sent')->default(false);
            $table->timestamp('invitation_sent_at')->nullable();
            $table->string('rsvp_token', 64)->unique()->nullable();
            $table->timestamp('rsvp_responded_at')->nullable();
            $table->boolean('plus_one')->default(false);
            $table->timestamps();

            $table->index('wedding_id');
            $table->index('guest_group_id');
            $table->index('rsvp_status');
            $table->index('rsvp_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
        Schema::dropIfExists('guest_groups');
    }
};
