<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_returns_empty_for_short_query(): void
    {
        $response = $this->getJson(route('search', ['q' => 'ab']));

        $response->assertOk();
        $response->assertJson(['products' => []]);
    }

    public function test_search_returns_matching_products(): void
    {
        Product::factory()->create([
            'name' => 'Handmade Olive Wood Cutting Board',
            'price' => 49.99,
            'is_active' => true,
            'in_stock' => true,
        ]);

        Product::factory()->create([
            'name' => 'Blue Ceramic Vase',
            'price' => 29.99,
            'is_active' => true,
            'in_stock' => true,
        ]);

        $response = $this->getJson(route('search', ['q' => 'olive']));

        $response->assertOk();
        $response->assertJsonCount(1, 'products');
        $response->assertJsonFragment(['name' => 'Handmade Olive Wood Cutting Board']);
    }

    public function test_search_only_returns_active_in_stock_products(): void
    {
        Product::factory()->create([
            'name' => 'Active Product',
            'is_active' => true,
            'in_stock' => true,
        ]);

        Product::factory()->create([
            'name' => 'Inactive Product',
            'is_active' => false,
            'in_stock' => true,
        ]);

        Product::factory()->create([
            'name' => 'Out of Stock Product',
            'is_active' => true,
            'in_stock' => false,
            'stock_quantity' => 0,
        ]);

        $response = $this->getJson(route('search', ['q' => 'product']));

        $response->assertOk();
        $response->assertJsonCount(1, 'products');
        $response->assertJsonFragment(['name' => 'Active Product']);
    }

    public function test_search_returns_product_data_with_url_and_price(): void
    {
        $product = Product::factory()->create([
            'name' => 'Jordanian Handicraft Basket',
            'price' => 35.00,
            'slug' => 'jordanian-handicraft-basket',
            'origin_type' => 'jordan',
            'is_active' => true,
            'in_stock' => true,
        ]);

        $response = $this->getJson(route('search', ['q' => 'basket']));

        $response->assertOk();
        $response->assertJsonFragment([
            'name' => 'Jordanian Handicraft Basket',
            'price' => '$35.00',
            'origin_type' => 'jordan',
        ]);
    }

    public function test_search_by_artisan_name(): void
    {
        Product::factory()->create([
            'name' => 'Handmade Pottery',
            'artisan_name' => 'Ahmad Ceramics Workshop',
            'is_active' => true,
            'in_stock' => true,
        ]);

        $response = $this->getJson(route('search', ['q' => 'ahmad']));

        $response->assertOk();
        $response->assertJsonCount(1, 'products');
    }

    public function test_search_empty_query_returns_empty(): void
    {
        $response = $this->getJson(route('search', ['q' => '']));

        $response->assertOk();
        $response->assertJson(['products' => []]);
    }
}
