@extends('layouts.app')

@section('title', 'My Wishlist - عالم نورا للكنوز')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-serif font-bold text-gray-800 mb-8">My Wishlist</h1>

    @if($wishlistItems->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($wishlistItems as $item)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <a href="{{ route('products.show', $item->product->slug) }}">
                <div class="aspect-square bg-cream">
                    @if($item->product->images->count() > 0)
                    <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-4xl">🏺</div>
                    @endif
                </div>
            </a>
            <div class="p-4">
                <a href="{{ route('products.show', $item->product->slug) }}">
                    <h3 class="font-semibold text-gray-800 hover:text-olive-500 transition">{{ $item->product->name }}</h3>
                </a>
                <p class="text-lg font-bold text-olive-500 mt-2">{{ $item->product->formatted_price }}</p>
                <div class="flex gap-2 mt-4">
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-olive-500 text-white py-2 rounded-lg text-sm font-semibold hover:bg-olive-600 transition">
                            Add to Cart
                        </button>
                    </form>
                    <form action="{{ route('wishlist.remove', $item) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 text-red-500 hover:text-red-600 border border-gray-300 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-16">
        <div class="text-6xl mb-4">💝</div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your wishlist is empty</h2>
        <p class="text-gray-600 mb-8">Start adding products you love to your wishlist.</p>
        <a href="{{ route('products.index') }}" class="bg-olive-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-olive-600 transition">
            Browse Products
        </a>
    </div>
    @endif
</div>
@endsection
