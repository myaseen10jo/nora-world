@extends('layouts.app')

@section('title', 'Shopping Cart - عالم نورا للكنوز')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-serif font-bold text-gray-800 mb-8">Shopping Cart</h1>

    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Cart Items --}}
        <div class="lg:col-span-2 space-y-4">
            @foreach($cartItems as $item)
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex gap-4">
                    <div class="w-24 h-24 bg-cream rounded-lg overflow-hidden flex-shrink-0">
                        @if($item->product->images->count() > 0)
                        <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-2xl">🏺</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $item->product->formatted_price }}</p>
                            </div>
                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <div class="mt-4">
                            <form action="{{ route('cart.update', $item) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100" onclick="this.nextElementSibling.stepDown(); this.closest('form').submit();">-</button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}" class="w-12 text-center border-0 focus:ring-0" onchange="this.closest('form').submit();">
                                        <button type="button" class="px-3 py-1 text-gray-600 hover:bg-gray-100" onclick="this.previousElementSibling.stepUp(); this.closest('form').submit();">+</button>
                                    </div>
                                    <span class="font-semibold text-olive-500">${{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Order Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg p-6 shadow-sm sticky top-24">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Order Summary</h2>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="text-sm text-gray-500">Calculated at checkout</span>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-800">Total</span>
                            <span class="font-bold text-xl text-olive-500">${{ number_format($subtotal, 2) }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="block w-full bg-olive-500 text-white py-3 rounded-lg font-semibold hover:bg-olive-600 transition text-center">
                    Proceed to Checkout
                </a>

                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-500 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Secure Checkout with PayPal
                    </p>
                </div>

                <a href="{{ route('products.index') }}" class="block text-center text-olive-500 hover:text-olive-600 mt-4 text-sm">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-16">
        <div class="text-6xl mb-4">🛒</div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your cart is empty</h2>
        <p class="text-gray-600 mb-8">Start shopping to add items to your cart.</p>
        <a href="{{ route('products.index') }}" class="bg-olive-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-olive-600 transition">
            Browse Products
        </a>
    </div>
    @endif
</div>
@endsection
