<div class="product-card group">
    <a href="{{ route('products.show', $product->slug) }}" class="block">
        {{-- Image Container --}}
        <div class="product-image aspect-square relative bg-stone-50">
            @php
                $primaryImage = $product->primaryImage ?? $product->images->first();
                $imagePath = function($path) {
                    if (!$path) return asset('images/placeholder-product.svg');
                    $trimmed = ltrim($path, '/');
                    return file_exists(public_path($trimmed)) ? asset($trimmed) : asset('storage/' . $path);
                };
            @endphp

            @if($primaryImage)
                <img
                    src="{{ $imagePath($primaryImage->path) }}"
                    alt="{{ $primaryImage->alt_text ?: $product->name }}"
                    class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                    loading="lazy"
                >
            @elseif($product->images->count() > 0)
                <img
                    src="{{ $imagePath($product->images->first()->path) }}"
                    alt="{{ $product->name }}"
                    class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                    loading="lazy"
                >
            @else
                <img
                    src="{{ asset('images/placeholder-product.svg') }}"
                    alt="{{ $product->name }}"
                    class="w-full h-full object-contain opacity-40 p-10"
                >
            @endif

            {{-- Hover overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

            {{-- Badges --}}
            @if($product->is_one_of_a_kind)
                <span class="absolute top-3 left-3 bg-amber-500/90 text-white text-[10px] font-medium px-2.5 py-1 rounded-full backdrop-blur-sm">
                    One of a Kind
                </span>
            @endif

            @if($product->is_on_sale)
                <span class="absolute top-3 right-3 bg-red-500/90 text-white text-[10px] font-medium px-2.5 py-1 rounded-full backdrop-blur-sm">
                    -{{ $product->discount_percentage }}%
                </span>
            @endif

            {{-- Quick view button --}}
            <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                <span class="quick-action-btn">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </span>
            </div>
        </div>
    </a>

    {{-- Product Info --}}
    <div class="p-4">
        @if($product->categories->count() > 0)
            <p class="text-[10px] font-medium text-stone-400 uppercase tracking-wider mb-1">
                {{ $product->categories->first()->name }}
            </p>
        @endif

        <a href="{{ route('products.show', $product->slug) }}">
            <h3 class="text-sm font-medium text-stone-800 line-clamp-2 leading-snug group-hover:text-stone-600 transition-colors">
                {{ $product->name }}
            </h3>
        </a>

        <div class="flex items-center gap-2 mt-2.5">
            <span class="text-sm font-semibold text-stone-900">{{ $product->formatted_price }}</span>
            @if($product->is_on_sale)
                <span class="text-xs text-stone-400 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
            @endif
        </div>
    </div>
</div>
