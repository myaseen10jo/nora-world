<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPricingTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_display_price_in_usd(): void
    {
        $product = Product::factory()->create([
            'price' => 49.99,
            'compare_at_price' => 59.99,
        ]);

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('$49.99');
        $response->assertSee('$59.99');
    }

    public function test_product_formatted_price_uses_usd(): void
    {
        $product = Product::factory()->create([
            'price' => 129.99,
        ]);

        $this->assertEquals('$129.99', $product->formatted_price);
    }

    public function test_product_is_on_sale_when_compare_at_price_is_higher(): void
    {
        $product = Product::factory()->create([
            'price' => 49.99,
            'compare_at_price' => 59.99,
        ]);

        $this->assertTrue($product->is_on_sale);
        $this->assertEquals(17, $product->discount_percentage);
    }

    public function test_product_is_not_on_sale_when_no_compare_at_price(): void
    {
        $product = Product::factory()->create([
            'price' => 49.99,
            'compare_at_price' => null,
        ]);

        $this->assertFalse($product->is_on_sale);
        $this->assertNull($product->discount_percentage);
    }

    public function test_cart_subtotal_calculates_correctly(): void
    {
        $user = \App\Models\User::factory()->create();
        $product = Product::factory()->create(['price' => 25.00]);

        $cartItem = \App\Models\CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->assertEquals(75.00, $cartItem->subtotal);
        $this->assertEquals('$75.00', $cartItem->formatted_subtotal);
    }
}
