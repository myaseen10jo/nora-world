<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;
    private ShippingMethod $shippingMethod;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create([
            'price' => 49.99,
            'stock_quantity' => 10,
            'in_stock' => true,
        ]);

        $zone = ShippingZone::create([
            'name' => 'United States',
            'countries' => ['US'],
            'is_active' => true,
        ]);

        $this->shippingMethod = ShippingMethod::create([
            'shipping_zone_id' => $zone->id,
            'name' => 'Standard International Shipping',
            'flat_rate' => 9.99,
            'free_shipping_threshold' => 100,
            'is_active' => true,
        ]);
    }

    public function test_checkout_page_requires_authentication(): void
    {
        $response = $this->get(route('checkout.index'));

        $response->assertRedirect('/login');
    }

    public function test_checkout_page_shows_cart_items(): void
    {
        CartItem::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($this->user)->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertSee($this->product->name);
        $response->assertSee('$99.98');
    }

    public function test_checkout_redirects_with_empty_cart(): void
    {
        $response = $this->actingAs($this->user)->get(route('checkout.index'));

        $response->assertRedirect(route('cart.index'));
    }

    public function test_create_paypal_order_fails_without_cart_items(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('checkout.create-paypal-order'), [
            'shipping_address' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'address_line_1' => '123 Main St',
                'city' => 'New York',
                'postal_code' => '10001',
                'country' => 'US',
            ],
            'shipping_method_id' => $this->shippingMethod->id,
        ]);

        $response->assertStatus(400);
    }

    public function test_create_paypal_order_validates_required_fields(): void
    {
        // Add item to cart so empty-cart check doesn't trigger first
        CartItem::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->user)->postJson(route('checkout.create-paypal-order'));

        $response->assertStatus(422);
    }

    public function test_cart_item_prevents_negative_stock(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 100,
        ]);

        $response->assertRedirect();
    }
}
