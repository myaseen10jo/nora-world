<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name' => ucfirst($name),
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => fake()->paragraph(),
            'short_description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 9.99, 299.99),
            'compare_at_price' => null,
            'sku' => strtoupper(fake()->lexify('???')) . '-' . fake()->numerify('###'),
            'stock_quantity' => fake()->numberBetween(1, 50),
            'in_stock' => true,
            'is_featured' => false,
            'is_active' => true,
            'weight' => fake()->randomFloat(2, 0.1, 5.0),
            'dimensions' => fake()->numerify('##x##x##') . ' cm',
            'origin_type' => fake()->randomElement(['jordan', 'palestine', 'jordan_and_palestine', 'other']),
            'origin_country' => null,
            'artisan_name' => fake()->name(),
            'product_story' => fake()->paragraph(),
            'materials_used' => fake()->words(5, true),
            'handmade_technique' => fake()->sentence(),
            'care_instructions' => fake()->sentence(),
            'estimated_preparation_time' => fake()->randomElement(['1-2 days', '2-3 days', '3-5 days']),
            'estimated_shipping_time' => fake()->randomElement(['7-14 business days', '10-18 business days']),
            'is_one_of_a_kind' => false,
            'is_made_to_order' => false,
            'cultural_note' => null,
            'gift_wrapping_available' => true,
        ];
    }
}
