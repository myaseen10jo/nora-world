<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->string('sku')->nullable()->unique();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('in_stock')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('dimensions')->nullable();

            // Handmade & heritage fields
            $table->enum('origin_type', ['jordan', 'palestine', 'jordan_and_palestine', 'other'])->default('other');
            $table->string('origin_country')->nullable();
            $table->string('artisan_name')->nullable();
            $table->text('product_story')->nullable();
            $table->text('materials_used')->nullable();
            $table->string('handmade_technique')->nullable();
            $table->text('care_instructions')->nullable();
            $table->string('estimated_preparation_time')->nullable();
            $table->string('estimated_shipping_time')->nullable();
            $table->boolean('is_one_of_a_kind')->default(false);
            $table->boolean('is_made_to_order')->default(false);
            $table->text('cultural_note')->nullable();
            $table->boolean('gift_wrapping_available')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
