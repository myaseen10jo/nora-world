@extends('layouts.app')

@section('title', $product->name . ' - NORA WORLD')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16 page-transition-enter">
    {{-- Breadcrumb --}}
    <nav class="text-xs text-stone-400 mb-8 flex items-center gap-2 anim-item anim-delay-1">
        <a href="{{ route('home') }}" class="hover:text-stone-700 transition-colors">Home</a>
        <span>·</span>
        <a href="{{ route('products.index') }}" class="hover:text-stone-700 transition-colors">Shop</a>
        @foreach($product->categories as $category)
        <span>·</span>
        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="hover:text-stone-700 transition-colors">{{ $category->name }}</a>
        @endforeach
        <span>·</span>
        <span class="text-stone-600">{{ $product->name }}</span>
    </nav>

    @php
        $imagePath = function($path) {
            if (!$path) return asset('images/placeholder-product.svg');
            $trimmed = ltrim($path, '/');
            return file_exists(public_path($trimmed)) ? asset($trimmed) : asset('storage/' . $path);
        };

        $colorMap = [
            'cream' => '#fef3c7', 'terracotta' => '#c2410c', 'navy' => '#1e3a5f',
            'clear' => '#e0e7ff', 'white' => '#f9fafb', 'gold' => '#d97706',
            'silver' => '#d1d5db', 'brown' => '#78350f', 'ochre' => '#ca8a04',
            'frosted' => '#e0e7ff', 'deep red' => '#b91c1c', 'deep-red' => '#b91c1c',
            'grey' => '#9ca3af', 'antique' => '#92400e', 'brass' => '#b45309',
            'patina' => '#8b7355', 'warm' => '#d97706', 'earth' => '#78350f',
            'earth tones' => '#92400e', 'mixed' => '#6b7280', 'natural' => '#78716c',
            'multi' => '#6b7280', 'vintage' => '#92400e', 'oxidized' => '#6b7280',
        ];
        $getColorHex = function($color) use ($colorMap) {
            $key = strtolower(trim($color ?? ''));
            return $colorMap[$key] ?? '#e5e7eb';
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
        {{-- Product Images with Lightbox --}}
        <div class="anim-item anim-delay-2">
            @include('components.product-gallery', [
                'images' => $product->images,
                'name' => $product->name,
                'imagePath' => $imagePath,
            ])
        </div>

        {{-- Product Info --}}
        <div class="anim-item anim-delay-3">
            {{-- Category & Badge --}}
            <div class="flex items-center gap-2 mb-3">
                @foreach($product->categories as $category)
                    <span class="badge">{{ $category->name }}</span>
                @endforeach                    @if($product->is_one_of_a_kind)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        One of a Kind
                    </span>
                @endif
            </div>

            <h1 class="text-3xl md:text-4xl font-serif font-bold text-stone-900 tracking-tight mb-3">
                {{ $product->name }}
            </h1>

            @if($product->artisan_name)
            <p class="text-sm text-stone-500 mb-4">by {{ $product->artisan_name }}</p>
            @endif

            {{-- Price --}}
            <div class="flex items-center gap-3 mb-8">
                <span class="text-3xl font-bold text-stone-900">{{ $product->formatted_price }}</span>
                @if($product->is_on_sale)
                <span class="text-lg text-stone-400 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
                <span class="bg-red-50 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-100">
                    -{{ $product->discount_percentage }}%
                </span>
                @endif
            </div>

            {{-- Description --}}
            <div class="text-stone-600 leading-relaxed mb-8 text-sm">
                {!! $product->short_description ?? $product->description !!}
            </div>

            {{-- Stock --}}
            <div class="mb-8">
                @if($product->in_stock && $product->stock_quantity > 0)
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span class="text-sm text-green-700 font-medium">In Stock</span>
                    @if($product->stock_quantity <= 5)
                    <span class="text-xs text-stone-400">· Only {{ $product->stock_quantity }} left</span>
                    @endif
                </div>
                @else
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-stone-300 rounded-full"></span>
                    <span class="text-sm text-stone-500 font-medium">Out of Stock</span>
                </div>
                @endif
            </div>

            {{-- Add to Cart --}}
            @auth
            @if($product->in_stock && $product->stock_quantity > 0)
            <form action="{{ route('cart.add') }}" method="POST" class="mb-8">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="flex items-center gap-4">
                    <div class="flex items-center border border-stone-200 rounded-lg">
                        <button type="button" class="px-4 py-3 text-stone-400 hover:text-stone-700 transition-colors" onclick="this.nextElementSibling.stepDown()">−</button>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-14 text-center border-0 bg-transparent text-sm font-medium focus:ring-0 text-stone-800">
                        <button type="button" class="px-4 py-3 text-stone-400 hover:text-stone-700 transition-colors" onclick="this.previousElementSibling.stepUp()">+</button>
                    </div>
                    <button type="submit" class="flex-1 btn-primary">
                        Add to Cart
                    </button>
                </div>
            </form>
            @endif
            @else
            <a href="{{ route('login') }}" class="block btn-primary text-center mb-8">
                Login to Purchase
            </a>
            @endauth

            {{-- Quick Details --}}
            <div class="border-t border-stone-100 pt-8 space-y-5">
                @if($product->clothing_size)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-stone-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Size</p>
                        <p class="text-sm text-stone-600 mt-0.5">{{ $product->clothing_size }}</p>
                    </div>
                </div>
                @endif

                @if($product->return_policy)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-stone-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Return Policy</p>
                        <p class="text-sm text-stone-600 mt-0.5">{{ $product->return_policy }}</p>
                    </div>
                </div>
                @endif

                @if($product->estimated_preparation_time)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-stone-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Preparation Time</p>
                        <p class="text-sm text-stone-600 mt-0.5">{{ $product->estimated_preparation_time }}</p>
                    </div>
                </div>
                @endif

                @if($product->gift_wrapping_available)
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-stone-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Gift Wrapping</p>
                        <p class="text-sm text-stone-600 mt-0.5">Available on request</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- EXTRACTED SPECIFICATIONS SECTION --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="mt-16 anim-item">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-8 h-px bg-stone-300"></div>
            <p class="text-[11px] text-stone-400 uppercase tracking-[0.25em] font-medium">Extracted Specifications</p>
            <div class="flex-1 h-px bg-stone-200"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Return Policy Card --}}
            <div class="bg-white rounded-xl p-6 border border-stone-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-stone-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-stone-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" /></svg>
                    </div>
                    <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Return Policy</h3>
                </div>
                <div class="space-y-3">
                    @if($product->return_policy)
                    <div>
                        <p class="text-sm font-medium text-stone-800">{{ $product->return_policy }}</p>
                    </div>
                    @else
                    <div>
                        <p class="text-sm text-stone-500">Final sale — no returns accepted</p>
                        <p class="text-xs text-stone-400 mt-1">All items are pre-loved vintage — please review description and photos before purchasing</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Color & Appearance Card --}}
            <div class="bg-white rounded-xl p-6 border border-stone-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-stone-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-stone-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 0 0 5.304 0l6.401-6.402M6.75 21A3.75 3.75 0 0 1 3 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 0 0 3.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008Z" /></svg>
                    </div>
                    <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Color & Appearance</h3>
                </div>
                <div class="space-y-3">
                    @if($product->color_palette)
                    <div>
                        <p class="text-xs text-stone-400">Color Palette</p>
                        <p class="text-sm font-medium text-stone-800">{{ $product->color_palette }}</p>
                    </div>
                    @endif
                    <div class="flex gap-4">
                        @if($product->color_primary)
                        <div>
                            <p class="text-[10px] text-stone-400 uppercase">Primary</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-4 h-4 rounded-full border border-stone-200" style="background: {{ $getColorHex($product->color_primary) }}"></span>
                                <p class="text-sm font-medium text-stone-800">{{ $product->color_primary }}</p>
                            </div>
                        </div>
                        @endif
                        @if($product->color_secondary)
                        <div>
                            <p class="text-[10px] text-stone-400 uppercase">Secondary</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-4 h-4 rounded-full border border-stone-200" style="background: {{ $getColorHex($product->color_secondary) }}"></span>
                                <p class="text-sm font-medium text-stone-800">{{ $product->color_secondary }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Condition & Age Card --}}
            <div class="bg-white rounded-xl p-6 border border-stone-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-stone-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-stone-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                    </div>
                    <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">Condition & Age</h3>
                </div>
                <div class="space-y-3">
                    @if($product->condition)
                    <div>
                        <p class="text-xs text-stone-400">Condition</p>
                        <p class="text-sm font-medium text-stone-800">{{ $product->condition }}</p>
                    </div>
                    @endif
                    @if($product->age_estimate)
                    <div>
                        <p class="text-xs text-stone-400">Age Estimate</p>
                        <p class="text-sm font-medium text-stone-800">{{ $product->age_estimate }}</p>
                    </div>
                    @endif
                    @if($product->style_notes)
                    <div>
                        <p class="text-xs text-stone-400">Style Notes</p>
                        <p class="text-sm font-medium text-stone-800">{{ $product->style_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Product Media Gallery (Videos & Content) --}}
    @if($product->media && $product->media->count() > 0)
    <div class="mt-16 anim-item">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-8 h-px bg-stone-300"></div>
            <p class="text-[11px] text-stone-400 uppercase tracking-[0.25em] font-medium">Media Gallery</p>
            <div class="flex-1 h-px bg-stone-200"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($product->media->where('is_active', true)->sortBy('sort_order') as $media)
            <div class="bg-white rounded-xl border border-stone-100 overflow-hidden">
                @if($media->type === 'youtube' || $media->type === 'vimeo')
                    {{-- Video Embed --}}
                    <div class="aspect-video">
                        <iframe src="{{ $media->embed_url }}"
                            class="w-full h-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                @elseif($media->type === 'video')
                    {{-- Uploaded Video --}}
                    <div class="aspect-video bg-stone-900">
                        <video controls class="w-full h-full object-cover" poster="{{ $media->display_url }}">
                            <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                        </video>
                    </div>
                @elseif($media->type === 'image')
                    {{-- Image --}}
                    <div class="aspect-square">
                        <img src="{{ $media->display_url }}" alt="{{ $media->title }}" class="w-full h-full object-cover">
                    </div>
                @elseif($media->type === 'content')
                    {{-- Rich Content --}}
                    <div class="p-6">
                        <div class="prose prose-sm prose-stone max-w-none">
                            {!! $media->content_html !!}
                        </div>
                    </div>
                @endif

                @if($media->title)
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-stone-900">{{ $media->title }}</h3>
                    @if($media->description)
                    <p class="text-xs text-stone-500 mt-1">{{ $media->description }}</p>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Product Story --}}
    @if($product->product_story)
    <div class="mt-16 bg-stone-50 rounded-2xl p-8 md:p-12 anim-item">
        <div class="max-w-2xl">
            <p class="text-[11px] text-stone-400 uppercase tracking-[0.25em] mb-4 font-medium">The Story</p>
            <h2 class="text-2xl font-serif font-bold text-stone-900 mb-6">Behind This Product</h2>
            <div class="text-stone-600 leading-relaxed text-sm">{{ $product->product_story }}</div>
        </div>
    </div>
    @endif

    {{-- Cultural Note --}}
    @if($product->cultural_note)
    <div class="mt-6 bg-amber-50 rounded-2xl p-6 md:p-8 border border-amber-100 anim-item">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" /></svg>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-amber-900 mb-1">Cultural Heritage Note</h3>
                <p class="text-sm text-amber-800/80 leading-relaxed">{{ $product->cultural_note }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Shipping Info --}}
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 anim-item">
        @if($product->estimated_preparation_time)
        <div class="bg-white rounded-xl p-5 border border-stone-100 flex items-center gap-3">
            <span class="text-xl">📦</span>
            <div>
                <p class="text-[10px] text-stone-400 uppercase tracking-wider">Preparation</p>
                <p class="text-sm font-medium text-stone-800">{{ $product->estimated_preparation_time }}</p>
            </div>
        </div>
        @endif
        @if($product->estimated_shipping_time)
        <div class="bg-white rounded-xl p-5 border border-stone-100 flex items-center gap-3">
            <span class="text-xl">✈️</span>
            <div>
                <p class="text-[10px] text-stone-400 uppercase tracking-wider">Shipping</p>
                <p class="text-sm font-medium text-stone-800">{{ $product->estimated_shipping_time }}</p>
            </div>
        </div>
        @endif
        @if($product->origin_country)
        <div class="bg-white rounded-xl p-5 border border-stone-100 flex items-center gap-3">
            <span class="text-xl">🌍</span>
            <div>
                <p class="text-[10px] text-stone-400 uppercase tracking-wider">Origin</p>
                <p class="text-sm font-medium text-stone-800">{{ $product->origin_country }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
    <div class="mt-20 anim-item">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-[11px] text-stone-400 uppercase tracking-[0.25em] mb-2 font-medium">You May Also Like</p>
                <h2 class="text-2xl font-serif font-bold text-stone-900">Related Treasures</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $product)
            <div class="anim-item anim-delay-{{ $loop->iteration }}">
                @include('components.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
