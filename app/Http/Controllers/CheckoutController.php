<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PayPalService;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(
        private PayPalService $paypalService,
        private ShippingService $shippingService,
    ) {}

    /**
     * Show checkout page
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $cartItems = CartItem::where('user_id', $user->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Calculate subtotal
        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        // Get shipping zones
        $zones = \App\Models\ShippingZone::active()->get();

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'zones' => $zones,
            'paypalClientId' => $this->paypalService->getClientId(),
            'isSandbox' => $this->paypalService->isSandbox(),
        ]);
    }

    /**
     * Get shipping methods for a country
     */
    public function getShippingMethods(Request $request)
    {
        $request->validate([
            'country' => 'required|string',
        ]);

        $methods = $this->shippingService->getAvailableMethods($request->country);

        return response()->json([
            'methods' => $methods,
            'is_served' => $this->shippingService->isCountryServed($request->country),
        ]);
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'method_id' => 'required|exists:shipping_methods,id',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $method = \App\Models\ShippingMethod::findOrFail($request->method_id);
        $cost = $this->shippingService->calculateCost($method, $request->subtotal);
        $freeShipping = $this->shippingService->isFreeShippingAvailable($method, $request->subtotal);

        return response()->json([
            'cost' => $cost,
            'free_shipping' => $freeShipping,
            'estimated_delivery' => $method->estimated_delivery_time,
        ]);
    }

    /**
     * Create PayPal order
     */
    public function createPayPalOrder(Request $request)
    {
        $user = $request->user();
        $cartItems = CartItem::where('user_id', $user->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $request->validate([
            'shipping_address' => 'required|array',
            'shipping_address.first_name' => 'required|string',
            'shipping_address.last_name' => 'required|string',
            'shipping_address.address_line_1' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.postal_code' => 'required|string',
            'shipping_address.country' => 'required|string',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
        ]);

        // Create order in database
        $order = DB::transaction(function () use ($request, $user, $cartItems) {
            $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
            $shippingMethod = \App\Models\ShippingMethod::findOrFail($request->shipping_method_id);
            $shippingCost = $this->shippingService->calculateCost($shippingMethod, $subtotal);
            $total = $subtotal + $shippingCost;

            // Create order
            $order = Order::create([
                'order_number' => 'NM-' . strtoupper(Str::random(10)),
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total' => $total,
                'currency' => 'USD',
                'shipping_first_name' => $request->shipping_address['first_name'],
                'shipping_last_name' => $request->shipping_address['last_name'],
                'shipping_company' => $request->shipping_address['company'] ?? null,
                'shipping_address_line_1' => $request->shipping_address['address_line_1'],
                'shipping_address_line_2' => $request->shipping_address['address_line_2'] ?? null,
                'shipping_city' => $request->shipping_address['city'],
                'shipping_state' => $request->shipping_address['state'] ?? null,
                'shipping_postal_code' => $request->shipping_address['postal_code'],
                'shipping_country' => $request->shipping_address['country'],
                'shipping_method' => $shippingMethod->name,
                'estimated_delivery' => $shippingMethod->estimated_delivery_time,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product->id,
                    'product_name' => $cartItem->product->name,
                    'product_slug' => $cartItem->product->slug,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price,
                    'total_price' => $cartItem->product->price * $cartItem->quantity,
                    'gift_wrapping' => $cartItem->gift_wrapping,
                    'gift_message' => $cartItem->gift_message,
                ]);
            }

            // Create pending payment
            Payment::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'amount' => $total,
                'currency' => 'USD',
                'payment_method' => 'paypal',
            ]);

            return $order;
        });

        // Create PayPal order
        try {
            $paypalOrder = $this->paypalService->createOrder($order, $order->total);

            return response()->json([
                'paypal_order_id' => $paypalOrder['id'],
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            $order->update(['status' => 'cancelled']);
            return response()->json(['error' => 'Failed to create PayPal order'], 500);
        }
    }

    /**
     * Handle PayPal success callback
     */
    public function paypalSuccess(Request $request)
    {
        $paypalOrderId = $request->query('token');

        if (!$paypalOrderId) {
            return redirect()->route('checkout.index')
                ->with('error', 'Invalid PayPal response.');
        }

        // Find the order by PayPal order ID
        $payment = \App\Models\Payment::where('paypal_order_id', $paypalOrderId)
            ->orWhere('id', $request->query('order_id'))
            ->first();

        if (!$payment) {
            return redirect()->route('checkout.index')
                ->with('error', 'Order not found.');
        }

        $order = $payment->order;

        try {
            // Capture the PayPal order
            $captureData = $this->paypalService->captureOrder($paypalOrderId);

            // Process the capture
            $this->paypalService->processSuccessfulCapture($order, $captureData);

            // Clear cart
            CartItem::where('user_id', $order->user_id)->delete();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Payment successful! Your order has been placed.');
        } catch (\Exception $e) {
            $this->paypalService->handlePaymentFailure($order, $e->getMessage());

            return redirect()->route('checkout.index')
                ->with('error', 'Payment failed. Please try again.');
        }
    }

    /**
     * Handle PayPal cancellation
     */
    public function paypalCancel(Request $request)
    {
        $paypalOrderId = $request->query('token');

        if ($paypalOrderId) {
            $payment = \App\Models\Payment::where('paypal_order_id', $paypalOrderId)->first();
            if ($payment) {
                $this->paypalService->handlePaymentCancellation($payment->order);
            }
        }

        return redirect()->route('checkout.index')
            ->with('info', 'Payment was cancelled. You can try again.');
    }
}
