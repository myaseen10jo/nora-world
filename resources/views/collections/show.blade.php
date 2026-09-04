@extends('layouts.app')

@section('title', $collection->name . ' - عالم نورا للكنوز')

@section('content')
<div class="pt-24 pb-16">
    {{-- Header --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <nav class="text-xs text-stone-400 mb-6 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-stone-700 transition-colors">Home</a>
            <span>·</span>
            <a href="{{ route('collections.index') }}" class="hover:text-stone-700 transition-colors">Collections</a>
            <span>·</span>
            <span class="text-stone-600">{{ $collection->name }}</span>
        </nav>

        <h1 class="text-3xl md:text-4xl font-serif font-bold text-stone-900 tracking-tight mb-2">{{ $collection->name }}</h1>
        @if($collection->description)
        <p class="text-sm text-stone-500 max-w-2xl">{{ $collection->description }}</p>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            @include('components.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-12">
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-20">
            <p class="text-lg text-stone-500 font-medium mb-4">No products in this collection yet.</p>
            <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center gap-2">
                Browse All Treasures
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
