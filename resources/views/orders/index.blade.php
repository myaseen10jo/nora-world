@extends('layouts.app')

@section('title', 'My Orders - عالم نورا للكنوز')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-serif font-bold text-gray-800 mb-8">My Orders</h1>

    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-gray-800">Order #{{ $order->order_number }}</h2>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('F j, Y') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status === 'shipped') bg-green-100 text-green-800
                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="font-bold text-olive-500">{{ $order->formatted_total }}</span>
                    <a href="{{ route('orders.show', $order) }}" class="text-olive-500 hover:text-olive-600 font-semibold text-sm">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
    @else
    <div class="text-center py-16">
        <div class="text-6xl mb-4">📦</div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">No orders yet</h2>
        <p class="text-gray-600 mb-8">Start shopping to place your first order.</p>
        <a href="{{ route('products.index') }}" class="bg-olive-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-olive-600 transition">
            Browse Products
        </a>
    </div>
    @endif
</div>
@endsection
