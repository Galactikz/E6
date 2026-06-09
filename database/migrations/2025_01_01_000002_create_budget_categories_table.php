<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('icon', 50)->nullable();
            $table->string('color', 20)->default('#6B7280');
            $table->decimal('planned_amount', 12, 2)->default(0);
            $table->decimal('actual_amount', 12, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_custom')->default(false);
            $table->timestamps();

            $table->index('wedding_id');
            $table->index(['wedding_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_categories');
    }
};
