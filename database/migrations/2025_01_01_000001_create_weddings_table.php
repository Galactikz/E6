<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name')->default('Mon Mariage');
            $table->date('wedding_date')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('venue_name', 255)->nullable();
            $table->unsignedInteger('guest_count')->default(0);
            $table->decimal('total_budget', 12, 2)->default(0);
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('wedding_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weddings');
    }
};
