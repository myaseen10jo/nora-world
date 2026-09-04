<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['image', 'video', 'youtube', 'vimeo', 'content'])->default('image');
            $table->string('title')->nullable();
            $table->string('file_path')->nullable()->comment('Path to uploaded file');
            $table->string('external_url')->nullable()->comment('YouTube/Vimeo URL');
            $table->text('content_html')->nullable()->comment('Rich HTML content');
            $table->text('description')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};
