<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $currency;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->currency = config('services.paypal.currency', 'USD');
        $this->baseUrl = config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Get OAuth2 access token
     */
    public function getAccessToken(): string
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Failed to get PayPal access token: ' . $response->body());
        }

        return $response->json('access_token');
    }

    /**
     * Create a PayPal order server-side
     */
    public function createOrder(Order $order, float $total): array
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => $order->order_number,
                        'amount' => [
                            'currency_code' => $this->currency,
                            'value' => number_format($total, 2, '.', ''),
                        ],
                        'description' => "NORA Order #{$order->order_number}",
                        'custom_id' => $order->id,
                    ],
                ],
                'application_context' => [
                    'brand_name' => 'NORA',
                    'locale' => 'en-US',
                    'landing_page' => 'BILLING',
                    'shipping_preference' => 'PROVIDED_READER_ADDRESS',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('checkout.paypal.success'),
                    'cancel_url' => route('checkout.paypal.cancel'),
                ],
            ]);

        if ($response->failed()) {
            Log::error('PayPal create order failed', [
                'order_id' => $order->id,
                'response' => $response->body(),
            ]);
            throw new \RuntimeException('Failed to create PayPal order: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Capture a PayPal order after buyer approval
     */
    public function captureOrder(string $paypalOrderId): array
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$paypalOrderId}/capture");

        if ($response->failed()) {
            Log::error('PayPal capture order failed', [
                'paypal_order_id' => $paypalOrderId,
                'response' => $response->body(),
            ]);
            throw new \RuntimeException('Failed to capture PayPal order: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Process a successful PayPal capture
     */
    public function processSuccessfulCapture(Order $order, array $captureData): Payment
    {
        // Validate the capture response
        $this->validateCaptureResponse($captureData);

        // Check for idempotency - prevent duplicate processing
        $paypalOrderId = $captureData['id'] ?? null;
        $existingPayment = Payment::where('paypal_order_id', $paypalOrderId)
            ->where('status', 'captured')
            ->first();

        if ($existingPayment) {
            Log::info('Duplicate PayPal capture detected, skipping', [
                'paypal_order_id' => $paypalOrderId,
            ]);
            return $existingPayment;
        }

        // Extract capture details
        $capture = $captureData['purchase_units'][0]['payments']['captures'][0] ?? null;
        if (!$capture) {
            throw new \RuntimeException('No capture found in PayPal response');
        }

        // Calculate total from database (never trust browser)
        $dbTotal = $order->items->sum(fn ($item) => $item->unit_price * $item->quantity);
        $dbTotal += $order->shipping_cost;

        // Validate amount matches
        $capturedAmount = (float) $capture['amount']['value'] ?? 0;
        if (abs($dbTotal - $capturedAmount) > 0.01) {
            Log::error('PayPal amount mismatch', [
                'order_id' => $order->id,
                'db_total' => $dbTotal,
                'captured_amount' => $capturedAmount,
            ]);
            throw new \RuntimeException('Payment amount does not match order total');
        }

        // Create payment record
        $payment = Payment::create([
            'order_id' => $order->id,
            'paypal_order_id' => $paypalOrderId,
            'paypal_capture_id' => $capture['id'] ?? null,
            'status' => 'captured',
            'amount' => $capturedAmount,
            'currency' => $capture['amount']['currency_code'] ?? $this->currency,
            'payer_email' => $captureData['payer']['email_address'] ?? null,
            'payer_id' => $captureData['payer']['payer_id'] ?? null,
            'payment_method' => 'paypal',
            'metadata' => $captureData,
            'captured_at' => now(),
            'idempotency_key' => $paypalOrderId,
        ]);

        // Update order status
        $order->update(['status' => 'processing']);

        // Reduce stock
        $this->reduceStock($order);

        return $payment;
    }

    /**
     * Validate PayPal capture response
     */
    private function validateCaptureResponse(array $captureData): void
    {
        if (!isset($captureData['status']) || $captureData['status'] !== 'COMPLETED') {
            throw new \RuntimeException('PayPal capture status is not COMPLETED');
        }

        if (!isset($captureData['purchase_units'][0]['payments']['captures'][0])) {
            throw new \RuntimeException('No captures found in PayPal response');
        }
    }

    /**
     * Reduce stock after successful payment
     */
    private function reduceStock(Order $order): void
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $newStock = max(0, $product->stock_quantity - $item->quantity);
                $product->update([
                    'stock_quantity' => $newStock,
                    'in_stock' => $newStock > 0,
                ]);
            }
        }
    }

    /**
     * Handle payment failure
     */
    public function handlePaymentFailure(Order $order, string $reason): Payment
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'status' => 'failed',
            'amount' => $order->total,
            'currency' => $this->currency,
            'payment_method' => 'paypal',
            'failure_reason' => $reason,
        ]);

        return $payment;
    }

    /**
     * Handle payment cancellation
     */
    public function handlePaymentCancellation(Order $order, ?string $reason = null): Payment
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'status' => 'cancelled',
            'amount' => $order->total,
            'currency' => $this->currency,
            'payment_method' => 'paypal',
            'failure_reason' => $reason ?? 'Customer cancelled payment',
        ]);

        return $payment;
    }

    /**
     * Get client ID for frontend
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * Check if in sandbox mode
     */
    public function isSandbox(): bool
    {
        return config('services.paypal.mode') === 'sandbox';
    }
}
