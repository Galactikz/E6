<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('Plan de table principal');
            $table->jsonb('room_dimensions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('wedding_id');
        });

        Schema::create('reception_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_plan_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('shape', 20)->default('round');
            $table->unsignedTinyInteger('capacity')->default(8);
            $table->jsonb('position')->nullable();
            $table->string('color', 20)->default('#6B7280');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('table_plan_id');
        });

        Schema::create('table_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reception_table_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('seat_number');
            $table->timestamps();

            $table->unique(['reception_table_id', 'seat_number']);
            $table->index('reception_table_id');
            $table->index('guest_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_seats');
        Schema::dropIfExists('reception_tables');
        Schema::dropIfExists('table_plans');
    }
};
