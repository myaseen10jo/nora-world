@extends('layouts.app')

@section('title', 'NORA WORLD WORLD — Vintage • Collectibles • Art • Pre-Loved Treasures')

@section('content')
<div x-data="scrollReveal()" x-init="init()">

{{-- ━━━━━━━━━━━━━━━ Hero Section ━━━━━━━━━━━━━━━ --}}
<section class="relative overflow-hidden bg-stone-900 text-white min-h-[90vh] flex items-center">
    <div class="absolute inset-0 hero-shimmer"></div>

    {{-- Subtle grain texture --}}
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 256 256%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%274%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>

    {{-- Floating orbs --}}
    <div class="absolute top-32 right-20 w-96 h-96 bg-amber-500/5 rounded-full blur-[100px] hero-float"></div>
    <div class="absolute bottom-20 left-10 w-64 h-64 bg-stone-400/5 rounded-full blur-[80px] hero-float" style="animation-delay: -4s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Left: Text --}}
            <div>
                <div class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-sm rounded-full px-4 py-1.5 mb-8 border border-white/10 page-enter" style="animation-delay: 0.1s;">
                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                    <span class="text-[11px] text-stone-400 uppercase tracking-widest font-medium">From Our Home to Yours</span>
                </div>

                <h1 class="text-6xl md:text-7xl lg:text-[5.5rem] font-serif font-bold mb-4 leading-[0.85] tracking-tight page-enter" style="animation-delay: 0.2s;">
                    NORA WORLD
                </h1>

                <p class="text-sm text-stone-400 tracking-[0.3em] uppercase mb-10 page-enter" style="animation-delay: 0.25s;">
                    Vintage · Collectibles · Art · Pre-Loved
                </p>

                <p class="text-lg md:text-xl mb-12 text-stone-300/80 leading-relaxed max-w-lg page-enter font-light" style="animation-delay: 0.35s;">
                    A carefully gathered collection of meaningful pieces that have lived in homes, witnessed ordinary days and special moments, and been treasured by the people who owned them.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 page-enter" style="animation-delay: 0.45s;">
                    <a href="{{ route('nora.gallery') }}" class="bg-white text-stone-900 px-8 py-4 rounded-xl font-medium text-sm tracking-wide uppercase transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98] flex items-center justify-center gap-2.5">
                        Explore Collection
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="{{ route('nora.about') }}" class="border border-white/20 text-white px-8 py-4 rounded-xl font-medium text-sm tracking-wide uppercase transition-all duration-300 hover:bg-white/5 hover:border-white/30 flex items-center justify-center gap-2">
                        Our Story
                    </a>
                </div>
            </div>

            {{-- Right: Product Grid --}}
            <div class="hidden lg:grid grid-cols-2 gap-4 page-enter" style="animation-delay: 0.3s;">
                <div class="space-y-4">
                    <a href="{{ route('products.show', 'vintage-ceramic-vase') }}" class="block rounded-2xl overflow-hidden aspect-[3/4] shadow-2xl hover:-translate-y-2 transition-transform duration-700">
                        <img src="{{ asset('images/nora/products/product-01.jpeg') }}" alt="Vintage Ceramic Vase" class="w-full h-full object-cover">
                    </a>
                    <a href="{{ route('products.show', 'vintage-timepiece') }}" class="block rounded-2xl overflow-hidden aspect-square shadow-2xl hover:-translate-y-2 transition-transform duration-700">
                        <img src="{{ asset('images/nora/products/product-09.jpeg') }}" alt="Vintage Timepiece" class="w-full h-full object-cover">
                    </a>
                </div>
                <div class="space-y-4 mt-12">
                    <a href="{{ route('products.show', 'handmade-folk-art-doll') }}" class="block rounded-2xl overflow-hidden aspect-square shadow-2xl hover:-translate-y-2 transition-transform duration-700">
                        <img src="{{ asset('images/nora/products/product-05.jpeg') }}" alt="Folk Art Doll" class="w-full h-full object-cover">
                    </a>
                    <a href="{{ route('products.show', 'vintage-handbag') }}" class="block rounded-2xl overflow-hidden aspect-[3/4] shadow-2xl hover:-translate-y-2 transition-transform duration-700">
                        <img src="{{ asset('images/nora/products/product-15.jpeg') }}" alt="Vintage Handbag" class="w-full h-full object-cover">
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ━━━━━━━━━━━━━━━ Categories ━━━━━━━━━━━━━━━ --}}
<section class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header reveal">
            <p class="text-[11px] text-stone-400 uppercase tracking-[0.25em] mb-4 font-medium">What You'll Discover</p>
            <h2 class="section-heading">Meaningful Pieces,<br>Thoughtfully Curated</h2>
            <p class="section-subheading mx-auto">Each category tells a story of craft, heritage, and the quiet beauty of things that last</p>
        </div>

        @php
        $categories = [
            ['icon' => '🏺', 'name' => 'Ceramics & Glassware', 'slug' => 'ceramics-glassware', 'desc' => 'Vintage tableware carrying the warmth of homes'],
            ['icon' => '🪆', 'name' => 'Decorative Objects & Art', 'slug' => 'decorative-objects-art', 'desc' => 'Art and objects that bring character to any space'],
            ['icon' => '⌚', 'name' => 'Watches & Jewellery', 'slug' => 'watches-jewellery', 'desc' => 'Timepieces ready for new chapters'],
            ['icon' => '🏆', 'name' => 'Collectibles', 'slug' => 'collectibles-commemorative', 'desc' => 'Treasures telling stories of culture and craft'],
            ['icon' => '👜', 'name' => 'Accessories & Handbags', 'slug' => 'accessories-handbags', 'desc' => 'Pre-loved treasures ready to be cherished'],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($categories as $index => $cat)
            <a href="{{ route('products.index', ['category' => $cat['slug']]) }}" class="category-card group reveal" style="transition-delay: {{ $index * 0.08 }}s;">
                <span class="text-3xl block mb-3 group-hover:scale-110 transition-transform duration-300">{{ $cat['icon'] }}</span>
                <h3 class="text-sm font-semibold text-stone-800 mb-1">{{ $cat['name'] }}</h3>
                <p class="text-[11px] text-stone-400 leading-relaxed">{{ $cat['desc'] }}</p>
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <span class="text-[10px] font-medium text-stone-500 uppercase tracking-wider">Browse →</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ━━━━━━━━━━━━━━━ Featured Products ━━━━━━━━━━━━━━━ --}}
<section class="section-padding bg-[#faf9f7]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-14">
            <div class="reveal">
                <p class="text-[11px] text-stone-400 uppercase tracking-[0.25em] mb-3 font-medium">From Our Collection</p>
                <h2 class="section-heading">Our Treasures</h2>
                <p class="section-subheading">Each piece has lived a story — now it's ready to begin another</p>
            </div>
            <a href="{{ route('nora.gallery') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition-colors flex items-center gap-1.5 group/link reveal">
                View Full Gallery
                <svg class="w-4 h-4 transition-transform duration-300 group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        @php
        $showcaseProducts = [
            ['image' => 'product-01.jpeg', 'name' => 'Vintage Ceramic Vase', 'slug' => 'vintage-ceramic-vase', 'category' => 'Ceramics & Glassware', 'price' => '$45'],
            ['image' => 'product-05.jpeg', 'name' => 'Folk Art Doll', 'slug' => 'handmade-folk-art-doll', 'category' => 'Decorative Objects & Art', 'price' => '$42'],
            ['image' => 'product-09.jpeg', 'name' => 'Vintage Timepiece', 'slug' => 'vintage-timepiece', 'category' => 'Watches & Jewellery', 'price' => '$85'],
            ['image' => 'product-12.jpeg', 'name' => 'Commemorative Plate', 'slug' => 'commemorative-collector-plate', 'category' => 'Collectibles', 'price' => '$58'],
            ['image' => 'product-03.jpeg', 'name' => 'Decorative Plate', 'slug' => 'ornate-decorative-plate', 'category' => 'Ceramics & Glassware', 'price' => '$52'],
            ['image' => 'product-07.jpeg', 'name' => 'Artistic Mirror', 'slug' => 'vintage-artistic-mirror', 'category' => 'Decorative Objects & Art', 'price' => '$65'],
            ['image' => 'product-15.jpeg', 'name' => 'Vintage Handbag', 'slug' => 'vintage-handbag', 'category' => 'Accessories & Handbags', 'price' => '$55'],
            ['image' => 'product-10.jpeg', 'name' => 'Heritage Necklace', 'slug' => 'heritage-necklace', 'category' => 'Watches & Jewellery', 'price' => '$48'],
        ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($showcaseProducts as $index => $product)
            <a href="{{ route('products.show', $product['slug']) }}" class="product-card group block reveal" style="transition-delay: {{ $index * 0.06 }}s;">
                <div class="product-image aspect-square relative">
                    <img src="{{ asset('images/nora/products/' . $product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0 pointer-events-none">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full shadow-sm">
                            <svg class="w-4 h-4 text-stone-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-[10px] font-medium text-stone-400 uppercase tracking-wider mb-1">{{ $product['category'] }}</p>
                    <h3 class="text-sm font-medium text-stone-800 group-hover:text-stone-600 transition-colors line-clamp-2">{{ $product['name'] }}</h3>
                    <p class="text-sm font-semibold text-stone-900 mt-2">{{ $product['price'] }}</p>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-14 reveal">
            <a href="{{ route('nora.gallery') }}" class="btn-primary inline-flex items-center gap-2.5">
                View All Our Treasures
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- ━━━━━━━━━━━━━━━ Philosophy ━━━━━━━━━━━━━━━ --}}
<section class="section-padding bg-stone-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 256 256%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%274%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/5 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-stone-500/5 rounded-full blur-[100px]"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="reveal-scale">
            <p class="text-[11px] text-stone-500 uppercase tracking-[0.3em] mb-8 font-medium">Our Philosophy</p>

            <h2 class="text-3xl md:text-5xl font-serif font-bold mb-10 leading-tight tracking-tight">
                Truth Comes<br>Before Marketing
            </h2>

            <blockquote class="text-lg md:text-xl mb-12 text-stone-400 leading-relaxed italic max-w-2xl mx-auto font-light">
                "Every piece is presented as honestly and respectfully as possible, using photographs of the actual item and clearly describing its condition. When an item's exact age, origin, or material cannot be confirmed, we prefer to say so rather than turn uncertainty into a claim."
            </blockquote>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('nora.about') }}" class="bg-white text-stone-900 px-8 py-4 rounded-xl font-medium text-sm tracking-wide uppercase transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98]">
                    Read Our Full Story
                </a>
                <a href="{{ route('nora.gallery') }}" class="border border-white/20 text-white px-8 py-4 rounded-xl font-medium text-sm tracking-wide uppercase transition-all duration-300 hover:bg-white/5 hover:border-white/30">
                    Browse the Collection
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ━━━━━━━━━━━━━━━ Closing Quote ━━━━━━━━━━━━━━━ --}}
<section class="section-padding bg-white">
    <div class="max-w-3xl mx-auto px-4 text-center reveal-scale">
        <div class="w-12 h-px bg-stone-200 mx-auto mb-8"></div>
        <p class="text-2xl md:text-3xl font-serif text-stone-700 italic leading-relaxed mb-4">
            "Every piece has lived a story."
        </p>
        <p class="text-lg md:text-xl font-serif font-semibold text-stone-500">
            Now it is ready to begin another — with you.
        </p>
        <div class="w-12 h-px bg-stone-200 mx-auto mt-8"></div>
    </div>
</section>

{{-- ━━━━━━━━━━━━━━━ Newsletter ━━━━━━━━━━━━━━━ --}}
<section class="py-20 bg-[#faf9f7]">
    <div class="max-w-xl mx-auto px-4 text-center reveal">
        <p class="text-[11px] text-stone-400 uppercase tracking-[0.25em] mb-4 font-medium">Stay Connected</p>
        <h2 class="text-2xl md:text-3xl font-serif font-bold text-stone-900 mb-3">Join Our Community</h2>
        <p class="text-sm text-stone-500 mb-8">Be the first to discover new treasures, stories, and collections.</p>

        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="email" name="email" required placeholder="Your email address" class="newsletter-input flex-1">
            <button type="submit" class="newsletter-btn whitespace-nowrap">Subscribe</button>
        </form>
        <p class="text-[11px] text-stone-400 mt-4">No spam. Unsubscribe anytime.</p>
    </div>
</section>

</div>
@endsection
