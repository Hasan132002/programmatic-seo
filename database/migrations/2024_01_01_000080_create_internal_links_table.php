<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->foreignId('source_page_id')->constrained('pages')->cascadeOnDelete();
            $table->foreignId('target_page_id')->constrained('pages')->cascadeOnDelete();
            $table->string('anchor_text', 255);
            $table->string('link_type', 20)->default('related');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_links');
    }
};
