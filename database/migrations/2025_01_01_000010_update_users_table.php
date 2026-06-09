<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('email');
            $table->string('apple_id')->nullable()->after('google_id');
            $table->string('avatar')->nullable()->after('apple_id');
            $table->string('phone', 20)->nullable()->after('avatar');
            $table->string('role', 20)->default('user')->after('phone');
            $table->string('locale', 10)->default('fr')->after('role');
            $table->boolean('marketing_consent')->default(false)->after('locale');
            $table->timestamp('last_login_at')->nullable()->after('marketing_consent');
            $table->string('stripe_customer_id')->nullable()->after('last_login_at');

            $table->index('google_id');
            $table->index('apple_id');
            $table->index('role');
            $table->index('stripe_customer_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'google_id', 'apple_id', 'avatar', 'phone',
                'role', 'locale', 'marketing_consent',
                'last_login_at', 'stripe_customer_id',
            ]);
        });
    }
};
