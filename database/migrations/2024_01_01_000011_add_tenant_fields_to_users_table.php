<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
            $table->foreignId('plan_id')->nullable()->after('is_admin')->constrained('plans')->nullOnDelete();
            $table->timestamp('plan_expires_at')->nullable()->after('plan_id');
            $table->integer('ai_credits_used')->default(0)->after('plan_expires_at');
            $table->string('api_token', 80)->nullable()->unique()->after('ai_credits_used');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['is_admin', 'plan_id', 'plan_expires_at', 'ai_credits_used', 'api_token']);
        });
    }
};
