@extends('layouts.app')

@section('title', 'Order #' . $order->order_number . ' - NORA WORLD')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-olive-500">Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('orders.index') }}" class="hover:text-olive-500">My Orders</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800">#{{ $order->order_number }}</span>
    </nav>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-serif font-bold text-gray-800">Order #{{ $order->order_number }}</h1>
        <span class="px-4 py-2 rounded-full text-sm font-medium
            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
            @elseif($order->status === 'shipped') bg-green-100 text-green-800
            @elseif($order->status === 'delivered') bg-green-100 text-green-800
            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Order Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order Items --}}
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Order Items</h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex gap-4 pb-4 border-b border-gray-100 last:border-0">
                        <div class="w-16 h-16 bg-cream rounded-lg overflow-hidden flex-shrink-0">
                            @if($item->product && $item->product->images->count() > 0)
                            <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-xl">🏺</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-800">{{ $item->product_name }}</h3>
                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                            @if($item->gift_wrapping)
                            <p class="text-xs text-green-600">🎁 Gift wrapped</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-olive-500">{{ $item->formatted_total_price }}</p>
                            <p class="text-sm text-gray-500">{{ $item->formatted_unit_price }} each</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Payment Information --}}
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Payment Information</h2>
                @if($order->payments->count() > 0)
                @foreach($order->payments as $payment)
                <div class="border border-gray-200 rounded-lg p-4 mb-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-800">PayPal Payment</p>
                            @if($payment->paypal_order_id)
                            <p class="text-sm text-gray-500">Order ID: {{ $payment->paypal_order_id }}</p>
                            @endif
                            @if($payment->payer_email)
                            <p class="text-sm text-gray-500">Email: {{ $payment->payer_email }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($payment->status === 'captured') bg-green-100 text-green-800
                                @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($payment->status === 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                            <p class="font-bold text-olive-500 mt-1">{{ $payment->formatted_amount }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <p class="text-gray-500">No payment information available.</p>
                @endif
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Order Total --}}
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Order Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium">${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    @if($order->tax_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium">${{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Discount</span>
                        <span class="font-medium text-green-600">-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-800">Total</span>
                            <span class="font-bold text-xl text-olive-500">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Shipping Address</h2>
                <div class="text-gray-600 text-sm space-y-1">
                    <p>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</p>
                    @if($order->shipping_company)
                    <p>{{ $order->shipping_company }}</p>
                    @endif
                    <p>{{ $order->shipping_address_line_1 }}</p>
                    @if($order->shipping_address_line_2)
                    <p>{{ $order->shipping_address_line_2 }}</p>
                    @endif
                    <p>{{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }}</p>
                    <p>{{ $order->shipping_postal_code }}</p>
                    <p>{{ $order->shipping_country }}</p>
                </div>
            </div>

            {{-- Shipping Method --}}
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Shipping Method</h2>
                <p class="text-gray-600">{{ $order->shipping_method }}</p>
                @if($order->estimated_delivery)
                <p class="text-sm text-gray-500 mt-1">Est. {{ $order->estimated_delivery }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
