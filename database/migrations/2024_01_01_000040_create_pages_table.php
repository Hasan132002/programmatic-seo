<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->string('title', 500);
            $table->string('slug', 500);
            $table->string('status', 20)->default('draft');
            $table->longText('content_html')->nullable();
            $table->json('content_json')->nullable();
            $table->json('variable_data')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->json('schema_markup')->nullable();
            $table->string('og_image', 500)->nullable();
            $table->decimal('priority', 2, 1)->default(0.5);
            $table->string('generation_method', 20)->default('manual');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['site_id', 'slug']);
            $table->index(['site_id', 'status']);
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
