<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('data_source_id')->constrained('data_sources')->cascadeOnDelete();
            $table->json('data')->nullable();
            $table->string('checksum', 64)->nullable();
            $table->timestamps();

            $table->index('data_source_id');
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_entries');
    }
};
