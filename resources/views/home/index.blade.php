@extends('layouts.app')

@section('title', 'عالم نورا للكنوز — زجاجيات انتيك • مقتنيات نسائية • تحف ورسومات • متفرقات')

@section('content')
<div x-data="scrollReveal()" x-init="init()">

{{-- ━━━━━━━━━━━━━━━ Hero Section ━━━━━━━━━━━━━━━ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-[#faf5ef] via-[#f5ede3] to-[#efe6d8] text-stone-800 min-h-[90vh] flex items-center">
    <div class="absolute inset-0 hero-shimmer"></div>

    {{-- Subtle grain texture --}}
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 256 256%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%274%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>

    {{-- Floating orbs --}}
    <div class="absolute top-32 right-20 w-96 h-96 bg-amber-400/8 rounded-full blur-[100px] hero-float"></div>
    <div class="absolute bottom-20 left-10 w-64 h-64 bg-stone-300/10 rounded-full blur-[80px] hero-float" style="animation-delay: -4s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 w-full">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Left: Text --}}
            <div>
                <div class="inline-flex items-center gap-2 bg-stone-800/5 backdrop-blur-sm rounded-full px-4 py-1.5 mb-8 border border-stone-300/20 page-enter" style="animation-delay: 0.1s;">
                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                    <span class="text-[11px] text-stone-500 uppercase tracking-widest font-medium">من بيوتنا لبيتك</span>
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-7xl font-serif font-bold mb-4 leading-[0.9] tracking-tight page-enter" style="animation-delay: 0.2s;">
                    عالم نورا للكنوز
                </h1>

                <p class="text-sm text-stone-500 tracking-[0.2em] uppercase mb-10 page-enter" style="animation-delay: 0.25s;">
                    زجاجيات انتيك · مقتنيات نسائية · تحف ورسومات · متفرقات
                </p>

                <p class="text-lg md:text-xl mb-12 text-stone-600/80 leading-relaxed max-w-lg page-enter font-light" style="animation-delay: 0.35s;">
                    مجموعة مختارة بعناية من القطع ذات المعنى التي عاشت في بيوت، شهدت أيام عادية ولحظات خاصة، و كانت محبوبة من أصحابها.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 page-enter" style="animation-delay: 0.45s;">
                    <a href="{{ route('nora.gallery') }}" class="bg-stone-800 text-white px-8 py-4 rounded-xl font-medium text-sm tracking-wide uppercase transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98] flex items-center justify-center gap-2.5">
                        استكشف المجموعة
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="{{ route('nora.about') }}" class="border border-stone-400/30 text-stone-700 px-8 py-4 rounded-xl font-medium text-sm tracking-wide uppercase transition-all duration-300 hover:bg-stone-800/5 hover:border-stone-400/40 flex items-center justify-center gap-2">
                        قصتنا
                    </a>
                </div>
            </div>

            {{-- Right: Store Image --}}
            <div class="hidden lg:block page-enter" style="animation-delay: 0.3s;">
                <div class="rounded-3xl overflow-hidden shadow-2xl hover:-translate-y-2 transition-transform duration-700">
                    <img src="{{ asset('images/nora/store-image.png') }}" alt="عالم نورا للكنوز - المتجر" class="w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ━━━━━━━━━━━━━━━ Categories ━━━━━━━━━━━━━━━ --}}
<section class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-header reveal">
            <p class="text-[11px] text-stone-500 uppercase tracking-[0.25em] mb-4 font-medium">ماذا ستجد</p>
            <h2 class="section-heading">قطع ذات معنى<br>مختارة بعناية</h2>
            <p class="section-subheading mx-auto">كل فئة تروي قصة من الحرفية والتراث والجمال الهادئ للأمور التي تدوم</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @php
            $categoryIcons = [
                'زجاجيات انتيك و فينتيج' => '🏺',
                'مقتنيات نسائية' => '👜',
                'تحف ورسومات' => '🪆',
                'متفرقات' => '📦',
            ];
            @endphp
            @foreach($featuredCategories as $index => $cat)
            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="category-card group reveal overflow-hidden" style="transition-delay: {{ $index * 0.08 }}s;">
                @if($cat->image)
                    <div class="w-full h-32 overflow-hidden rounded-lg mb-3">
                        <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                @else
                    <span class="text-3xl block mb-3 group-hover:scale-110 transition-transform duration-300">{{ $categoryIcons[$cat->name] ?? '📦' }}</span>
                @endif
                <h3 class="text-sm font-semibold text-stone-800 mb-1">{{ $cat->name }}</h3>
                <p class="text-[10px] text-stone-400">{{ $cat->products_count }} {{ $cat->products_count == 1 ? 'قطعة' : 'قطع' }}</p>
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
                <p class="text-[11px] text-stone-500 uppercase tracking-[0.25em] mb-3 font-medium">من مجموعتنا</p>
                <h2 class="section-heading">كنوزنا</h2>
                <p class="section-subheading">كل قطعة عاشت قصة — والآن مستعدة لبدء أخرى</p>
            </div>
            <a href="{{ route('nora.gallery') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900 transition-colors flex items-center gap-1.5 group/link reveal">
                عرض المجموعة الكاملة
                <svg class="w-4 h-4 transition-transform duration-300 group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        @php
            $displayProducts = $bestSellers->count() > 0 ? $bestSellers : ($newArrivals->count() > 0 ? $newArrivals : $onSale);
        @endphp

        @if($displayProducts->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($displayProducts as $index => $product)
            @php
                $img = $product->primaryImage ?? $product->images->first();
                $imgPath = $img ? asset('storage/' . $img->path) : asset('images/placeholder-product.svg');
                $catName = $product->categories->first()?->name ?? '';
            @endphp
            <a href="{{ route('products.show', $product->slug) }}" class="product-card group block reveal" style="transition-delay: {{ $index * 0.06 }}s;">
                <div class="product-image aspect-square relative">
                    <img src="{{ $imgPath }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0 pointer-events-none">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full shadow-sm">
                            <svg class="w-4 h-4 text-stone-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                    @if($product->is_on_sale)
                        <span class="absolute top-3 right-3 bg-red-500/90 text-white text-[10px] font-medium px-2.5 py-1 rounded-full backdrop-blur-sm">
                            -{{ $product->discount_percentage }}%
                        </span>
                    @endif
                    @if($product->is_one_of_a_kind)
                        <span class="absolute top-3 left-3 bg-amber-500/90 text-white text-[10px] font-medium px-2.5 py-1 rounded-full backdrop-blur-sm">
                            One of a Kind
                        </span>
                    @endif
                </div>
                <div class="p-4">
                    @if($catName)
                    <p class="text-[10px] font-medium text-stone-400 uppercase tracking-wider mb-1">{{ $catName }}</p>
                    @endif
                    <h3 class="text-sm font-medium text-stone-800 group-hover:text-stone-600 transition-colors line-clamp-2">{{ $product->name }}</h3>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-sm font-semibold text-stone-900">{{ $product->formatted_price }}</span>
                        @if($product->is_on_sale)
                            <span class="text-xs text-stone-400 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-16">
            <p class="text-stone-400 text-sm">No products available yet.</p>
        </div>
        @endif

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
            <p class="text-[11px] text-stone-500 uppercase tracking-[0.3em] mb-8 font-medium">فلسفتنا</p>

            <h2 class="text-3xl md:text-5xl font-serif font-bold mb-10 leading-tight tracking-tight">
                الحقيقة تأتي<br>قبل التسويق
            </h2>

            <blockquote class="text-lg md:text-xl mb-12 text-stone-400 leading-relaxed italic max-w-2xl mx-auto font-light">
                "كل قطعة تُقدّم بأمانة واحترام قدر الإمكان، باستخدام صور للعنصر الفعلي ووصف دقيق لحالته. عندما لا يمكن تأكيد العمر الأصلي أو الأصل أو المادة بالضبط، نفضل أن نقول ذلك بدلاً من تحويل عدم اليقين إلى ادعاء."
            </blockquote>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('nora.about') }}" class="bg-white text-stone-900 px-8 py-4 rounded-xl font-medium text-sm tracking-wide uppercase transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98]">
                    اقرأ قصتنا الكاملة
                </a>
                <a href="{{ route('nora.gallery') }}" class="border border-white/20 text-white px-8 py-4 rounded-xl font-medium text-sm tracking-wide uppercase transition-all duration-300 hover:bg-white/5 hover:border-white/30">
                    تصفح المجموعة
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
            "كل قطعة عاشت قصة."
        </p>
        <p class="text-lg md:text-xl font-serif font-semibold text-stone-500">
            والآن مستعدة لبدء أخرى — معك.
        </p>
        <div class="w-12 h-px bg-stone-200 mx-auto mt-8"></div>
    </div>
</section>

{{-- ━━━━━━━━━━━━━━━ Newsletter ━━━━━━━━━━━━━━━ --}}
<section class="py-20 bg-[#faf9f7]">
    <div class="max-w-xl mx-auto px-4 text-center reveal">
        <p class="text-[11px] text-stone-500 uppercase tracking-[0.25em] mb-4 font-medium">ابقَ على تواصل</p>
        <h2 class="text-2xl md:text-3xl font-serif font-bold text-stone-900 mb-3">انضم لمجتمعنا</h2>
        <p class="text-sm text-stone-500 mb-8">كن أول من يكتشف الكنوز والقصص والمجموعات الجديدة.</p>

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
