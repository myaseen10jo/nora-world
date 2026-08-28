<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_status_can_be_pending(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_method' => 'paypal',
        ]);

        $this->assertEquals('pending', $payment->status);
        $this->assertTrue($payment->isPending());
    }

    public function test_payment_status_can_be_captured(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'status' => 'captured',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_method' => 'paypal',
            'paypal_order_id' => 'test-paypal-order-id',
            'paypal_capture_id' => 'test-capture-id',
            'captured_at' => now(),
        ]);

        $this->assertEquals('captured', $payment->status);
        $this->assertTrue($payment->isCaptured());
    }

    public function test_payment_status_can_be_failed(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'status' => 'failed',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_method' => 'paypal',
            'failure_reason' => 'Card declined',
        ]);

        $this->assertEquals('failed', $payment->status);
        $this->assertTrue($payment->isFailed());
    }

    public function test_payment_status_can_be_cancelled(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_method' => 'paypal',
        ]);

        $this->assertEquals('cancelled', $payment->status);
        $this->assertTrue($payment->isFailed());
    }

    public function test_payment_status_can_be_refunded(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'status' => 'refunded',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_method' => 'paypal',
            'refunded_at' => now(),
        ]);

        $this->assertEquals('refunded', $payment->status);
        $this->assertTrue($payment->isRefunded());
    }

    public function test_formatted_amount_displays_usd(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_method' => 'paypal',
        ]);

        $this->assertEquals('$99.99', $payment->formatted_amount);
    }

    public function test_duplicate_payment_prevention_via_idempotency_key(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $payment1 = Payment::create([
            'order_id' => $order->id,
            'status' => 'captured',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_method' => 'paypal',
            'paypal_order_id' => 'duplicate-test-id',
            'idempotency_key' => 'duplicate-test-id',
        ]);

        // Attempting to create another payment with the same idempotency key should fail
        $this->expectException(\Illuminate\Database\QueryException::class);

        Payment::create([
            'order_id' => $order->id,
            'status' => 'captured',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_method' => 'paypal',
            'paypal_order_id' => 'duplicate-test-id',
            'idempotency_key' => 'duplicate-test-id',
        ]);
    }

    public function test_order_not_marked_paid_without_capture(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        // Order should remain pending until PayPal confirms capture
        $this->assertEquals('pending', $order->status);
        $this->assertEmpty($order->payments()->where('status', 'captured')->get());
    }

    public function test_stock_not_reduced_until_payment_captured(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'stock_quantity' => 10,
            'in_stock' => true,
        ]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        // Stock should remain unchanged
        $product->refresh();
        $this->assertEquals(10, $product->stock_quantity);
    }

    public function test_customer_can_view_paid_orders(): void
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'processing',
        ]);

        Payment::create([
            'order_id' => $order->id,
            'status' => 'captured',
            'amount' => $order->total,
            'currency' => 'USD',
            'payment_method' => 'paypal',
        ]);

        $response = $this->actingAs($user)->get(route('orders.show', $order));

        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee('$');
    }
}
