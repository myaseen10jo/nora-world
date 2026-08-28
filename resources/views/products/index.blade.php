@extends('layouts.app')

@section('title', 'Shop All Treasures - NORA')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16">
    {{-- Page Header --}}
    <div class="mb-10">
        <p class="text-[11px] text-stone-400 uppercase tracking-[0.25em] mb-3 font-medium">Our Collection</p>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-serif font-bold text-stone-900 tracking-tight">
                    @if($search)
                        Search results for "{{ $search }}"
                    @elseif($currentCategory)
                        {{ $categories->firstWhere('slug', $currentCategory)?->name ?? 'Products' }}
                    @else
                        All Treasures
                    @endif
                </h1>
                <p class="text-sm text-stone-500 mt-2">{{ $products->total() }} {{ Str::plural('item', $products->total()) }}</p>
            </div>
            @if($currentCategory || $search)
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-stone-500 hover:text-stone-800 transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    Clear filters
                </a>
            @endif
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-10">
        {{-- Sidebar Filters --}}
        <aside class="w-full lg:w-56 flex-shrink-0">
            <div class="space-y-8 lg:sticky lg:top-24">
                {{-- Categories --}}
                <div>
                    <h3 class="text-[11px] font-semibold text-stone-900 uppercase tracking-widest mb-4">Categories</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('products.index') }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ !$currentCategory ? 'bg-stone-900 text-white font-medium' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }}">
                                <span>All Treasures</span>
                                <span class="text-xs {{ !$currentCategory ? 'text-stone-400' : 'text-stone-400' }}">{{ $products->total() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors {{ $currentCategory === $category->slug ? 'bg-stone-900 text-white font-medium' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }}">
                                <span>{{ $category->name }}</span>
                                <span class="text-xs {{ $currentCategory === $category->slug ? 'text-stone-400' : 'text-stone-400' }}">{{ $category->products_count }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Origin Filter --}}
                <div>
                    <h3 class="text-[11px] font-semibold text-stone-900 uppercase tracking-widest mb-4">Origin</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('products.index', array_merge(request()->except('origin'), ['origin' => 'jordan'])) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ $currentOrigin === 'jordan' ? 'bg-stone-900 text-white font-medium' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }}">
                                <span>🇯🇴</span> Jordan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index', array_merge(request()->except('origin'), ['origin' => 'palestine'])) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ $currentOrigin === 'palestine' ? 'bg-stone-900 text-white font-medium' : 'text-stone-600 hover:bg-stone-50 hover:text-stone-900' }}">
                                <span>🇵🇸</span> Palestine
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        {{-- Products Grid --}}
        <div class="flex-1">
            @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                @include('components.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-12">
                {{ $products->links() }}
            </div>
            @else
            <div class="text-center py-20">
                <div class="text-4xl mb-4">🔍</div>
                <p class="text-lg text-stone-600 font-medium mb-2">No products found</p>
                <p class="text-sm text-stone-400 mb-6">Try adjusting your filters or search terms</p>
                <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center gap-2">
                    Browse All Treasures
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
