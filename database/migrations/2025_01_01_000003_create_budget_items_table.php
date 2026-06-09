<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wedding_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('notes')->nullable();
            $table->decimal('planned_amount', 12, 2)->default(0);
            $table->decimal('actual_amount', 12, 2)->default(0);
            $table->boolean('is_paid')->default(false);
            $table->date('due_date')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->index('wedding_id');
            $table->index('budget_category_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};
