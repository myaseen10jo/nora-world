@extends('layouts.app')

@section('title', $product->name . ' - عالم نورا للكنوز')

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
            <div class="flex items-center gap-2 mb-4">
                @foreach($product->categories as $category)
                    <span class="badge">{{ $category->name }}</span>
                @endforeach
                @if($product->is_one_of_a_kind)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                        ✨ One of a Kind
                    </span>
                @endif
            </div>

            <h1 class="text-3xl md:text-4xl font-serif font-bold text-stone-900 tracking-tight mb-3 leading-tight">
                {{ $product->name }}
            </h1>
            @if($product->sku)
            <p class="text-[11px] text-stone-400 font-mono mb-4">رقم القطعة: {{ $product->sku }}</p>
            @endif

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
                    <span class="text-stone-300 mt-0.5">📏</span>
                    <div>
                        <p class="text-xs font-semibold text-stone-900 uppercase tracking-wider">المقاس</p>
                        <p class="text-sm text-stone-600 mt-0.5">{{ $product->clothing_size }}</p>
                    </div>
                </div>
                @endif

                @if($product->return_policy)
                <div class="flex items-start gap-3">
                    <span class="text-stone-300 mt-0.5">📋</span>
                    <div>
                        <p class="text-xs font-semibold text-stone-900 uppercase tracking-wider">سياسة الإرجاع</p>
                        <p class="text-sm text-stone-600 mt-0.5">{{ $product->return_policy }}</p>
                    </div>
                </div>
                @endif

                @if($product->estimated_preparation_time)
                <div class="flex items-start gap-3">
                    <span class="text-stone-300 mt-0.5">⏱</span>
                    <div>
                        <p class="text-xs font-semibold text-stone-900 uppercase tracking-wider">وقت التحضير</p>
                        <p class="text-sm text-stone-600 mt-0.5">{{ $product->estimated_preparation_time }}</p>
                    </div>
                </div>
                @endif

                @if($product->gift_wrapping_available)
                <div class="flex items-start gap-3">
                    <span class="text-stone-300 mt-0.5">🎁</span>
                    <div>
                        <p class="text-xs font-semibold text-stone-900 uppercase tracking-wider">تغليف هدايا</p>
                        <p class="text-sm text-stone-600 mt-0.5">متاح عند الطلب</p>
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
            <p class="text-[11px] text-stone-400 uppercase tracking-[0.25em] font-medium">المواصفات</p>
            <div class="flex-1 h-px bg-stone-200"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Color & Appearance Card --}}
            <div class="bg-white rounded-xl p-6 border border-stone-100">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg">🎨</span>
                    <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">اللون والمظهر</h3>
                </div>
                <div class="space-y-3">
                    @if($product->color_palette)
                    <div>
                        <p class="text-xs text-stone-400">لوحة الألوان</p>
                        <p class="text-sm font-medium text-stone-800">{{ $product->color_palette }}</p>
                    </div>
                    @endif
                    <div class="flex gap-4">
                        @if($product->color_primary)
                        <div>
                            <p class="text-[10px] text-stone-400 uppercase">اللون الأساسي</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-4 h-4 rounded-full border border-stone-200" style="background: {{ $getColorHex($product->color_primary) }}"></span>
                                <p class="text-sm font-medium text-stone-800">{{ $product->color_primary }}</p>
                            </div>
                        </div>
                        @endif
                        @if($product->color_secondary)
                        <div>
                            <p class="text-[10px] text-stone-400 uppercase">اللون الثانوي</p>
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
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg">🔍</span>
                    <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">الحالة والعمر</h3>
                </div>
                <div class="space-y-3">
                    @if($product->condition)
                    <div>
                        <p class="text-xs text-stone-400">الحالة</p>
                        <p class="text-sm font-medium text-stone-800">{{ $product->condition }}</p>
                    </div>
                    @endif
                    @if($product->age_estimate)
                    <div>
                        <p class="text-xs text-stone-400">تقدير العمر</p>
                        <p class="text-sm font-medium text-stone-800">{{ $product->age_estimate }}</p>
                    </div>
                    @endif
                    @if($product->style_notes)
                    <div>
                        <p class="text-xs text-stone-400">ملاحظات الأسلوب</p>
                        <p class="text-sm font-medium text-stone-800">{{ $product->style_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Return Policy Card --}}
            <div class="bg-white rounded-xl p-6 border border-stone-100">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg">📋</span>
                    <h3 class="text-xs font-semibold text-stone-900 uppercase tracking-wider">سياسة الإرجاع</h3>
                </div>
                <div class="space-y-3">
                    @if($product->return_policy)
                    <div>
                        <p class="text-sm font-medium text-stone-800">{{ $product->return_policy }}</p>
                    </div>
                    @else
                    <div>
                        <p class="text-sm text-stone-500">البيع نهائي — لا يُسمح بالإرجاع</p>
                        <p class="text-xs text-stone-400 mt-1">جميع القطع vintage و مستعملة — يُرجى مراجعة الوصف والصور قبل الشراء</p>
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
            <span class="text-amber-600 mt-0.5">🏛️</span>
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
                <p class="text-[10px] text-stone-400 uppercase tracking-wider">التحضير</p>
                <p class="text-sm font-medium text-stone-800">{{ $product->estimated_preparation_time }}</p>
            </div>
        </div>
        @endif
        @if($product->estimated_shipping_time)
        <div class="bg-white rounded-xl p-5 border border-stone-100 flex items-center gap-3">
            <span class="text-xl">✈️</span>
            <div>
                <p class="text-[10px] text-stone-400 uppercase tracking-wider">الشحن</p>
                <p class="text-sm font-medium text-stone-800">{{ $product->estimated_shipping_time }}</p>
            </div>
        </div>
        @endif
        @if($product->origin_country)
        <div class="bg-white rounded-xl p-5 border border-stone-100 flex items-center gap-3">
            <span class="text-xl">🌍</span>
            <div>
                <p class="text-[10px] text-stone-400 uppercase tracking-wider">المنشأ</p>
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
