@extends('layouts.app')

@section('title', 'Collection Gallery — NORA')

@section('content')
<div x-data="{ activeCategory: 'all' }">

{{-- Hero --}}
<section class="relative overflow-hidden bg-stone-900 text-white py-24">
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 256 256%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%274%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-[11px] text-stone-500 uppercase tracking-[0.3em] mb-4 font-medium">Curated Collection</p>
        <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4 tracking-tight">Our Collection</h1>
        <p class="text-base text-stone-400 max-w-xl mx-auto">
            Treasures with a past, ready for a new home. Each piece has been carefully chosen and honestly presented.
        </p>
    </div>
</section>

{{-- Category Filter --}}
<section class="sticky top-16 z-40 bg-white/80 backdrop-blur-xl border-b border-stone-100 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pb-1">
            <button
                @click="activeCategory = 'all'"
                :class="activeCategory === 'all' ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                class="flex-shrink-0 px-5 py-2 rounded-full text-xs font-medium transition-all duration-300">
                All Treasures
            </button>

            @foreach($categories as $category)
            <button
                @click="activeCategory = '{{ $category['name'] }}'"
                :class="activeCategory === '{{ $category['name'] }}' ? 'bg-stone-900 text-white' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                class="flex-shrink-0 px-5 py-2 rounded-full text-xs font-medium transition-all duration-300">
                {{ $category['icon'] }} {{ $category['name'] }}
            </button>
            @endforeach
        </div>
    </div>
</section>

{{-- Product Gallery --}}
<section class="py-16 bg-[#faf9f7]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @foreach($categories as $catIndex => $category)
        <div x-show="activeCategory === 'all' || activeCategory === '{{ $category['name'] }}'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-2xl">{{ $category['icon'] }}</span>
                    <div>
                        <h2 class="text-xl font-serif font-bold text-stone-900">{{ $category['name'] }}</h2>
                    </div>
                </div>
                <p class="text-sm text-stone-500 mb-3">{{ $category['description'] }}</p>
                <div class="h-px bg-stone-200"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-16">
                @foreach($category['products'] as $pIndex => $product)
                <div class="product-card group">
                    <div class="product-image aspect-square">
                        <img
                            src="{{ asset('images/nora/products/' . $product['image']) }}"
                            alt="{{ $product['name'] }}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-stone-700 text-[10px] font-medium px-2.5 py-1 rounded-full shadow-sm">
                            {{ $product['tag'] }}
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-medium text-stone-800 group-hover:text-stone-600 transition-colors">{{ $product['name'] }}</h3>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

    </div>
</section>

{{-- CTA --}}
<section class="py-20 bg-stone-900 text-white">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4">Every Piece Has Lived a Story</h2>
        <p class="text-stone-400 text-lg mb-8">
            Now it is ready to begin another — with you.
        </p>
        <a href="{{ route('nora.about') }}" class="bg-white text-stone-900 px-8 py-4 rounded-xl font-medium text-sm tracking-wide uppercase transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 inline-flex items-center gap-2">
            Read Our Story
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </a>
    </div>
</section>

</div>
@endsection
