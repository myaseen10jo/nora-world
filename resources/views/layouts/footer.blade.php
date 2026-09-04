<footer class="bg-stone-900 text-stone-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Top Section --}}
        <div class="py-16 grid grid-cols-1 md:grid-cols-12 gap-12">
            {{-- Brand --}}
            <div class="md:col-span-4">
                <div class="flex items-center gap-2.5 mb-5">
                    <img src="{{ asset('images/nora/logo-new.png') }}" alt="عالم نورا للكنوز" class="h-9 w-auto object-contain opacity-90">
                </div>
                <p class="text-stone-500 text-sm leading-relaxed mb-6 max-w-xs">
                    عالم نورا للكنوز — من بيوتنا لبيتك. قطع فريدة تحكي قصصاً.
                </p>
                <p class="text-stone-600 text-xs leading-relaxed max-w-xs">
                    Every piece has lived a story. Now it is ready to begin another — with you.
                </p>
            </div>

            {{-- Navigation --}}
            <div class="md:col-span-2">
                <h4 class="text-[11px] font-semibold text-stone-300 uppercase tracking-widest mb-5">Explore</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('nora.gallery') }}" class="text-sm text-stone-500 hover:text-amber-400 transition-colors">Gallery</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-sm text-stone-500 hover:text-amber-400 transition-colors">Shop</a></li>
                    <li><a href="{{ route('collections.index') }}" class="text-sm text-stone-500 hover:text-amber-400 transition-colors">Collections</a></li>
                    <li><a href="{{ route('nora.about') }}" class="text-sm text-stone-500 hover:text-amber-400 transition-colors">Our Story</a></li>
                </ul>
            </div>

            {{-- Categories --}}
            <div class="md:col-span-3">
                <h4 class="text-[11px] font-semibold text-stone-300 uppercase tracking-widest mb-5">Categories</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('products.index', ['category' => 'zajagiat-antique-vintage']) }}" class="text-sm text-stone-500 hover:text-amber-400 transition-colors">زجاجيات انتيك و فينتيج</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'maqtniat-nisaiyah']) }}" class="text-sm text-stone-500 hover:text-amber-400 transition-colors">مقتنيات نسائية</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'tuhaf-w-rusumat']) }}" class="text-sm text-stone-500 hover:text-amber-400 transition-colors">تحف ورسومات</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'mutafarriqat']) }}" class="text-sm text-stone-500 hover:text-amber-400 transition-colors">متفرقات</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="md:col-span-3">
                <h4 class="text-[11px] font-semibold text-stone-300 uppercase tracking-widest mb-5">Contact</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <span class="text-stone-600 mt-0.5">📍</span>
                        <span>Amman, Jordan</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-stone-600 mt-0.5">📧</span>
                        <a href="mailto:hello@nora.com" class="hover:text-amber-400 transition-colors">hello@nora.com</a>
                    </li>
                </ul>

                <div class="mt-6 flex gap-2">
                    <a href="#" class="w-9 h-9 bg-stone-800 hover:bg-amber-600 rounded-full flex items-center justify-center text-stone-400 hover:text-white transition-all text-xs font-medium">IG</a>
                    <a href="#" class="w-9 h-9 bg-stone-800 hover:bg-amber-600 rounded-full flex items-center justify-center text-stone-400 hover:text-white transition-all text-xs font-medium">FB</a>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="py-6 border-t border-stone-800 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-stone-600 text-xs">
                © {{ date('Y') }} عالم نورا للكنوز. All rights reserved.
            </p>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-1.5 text-stone-600 text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Secure PayPal Checkout
                </div>
                <div class="flex items-center gap-1.5 text-stone-600 text-xs">
                    ✈️ International Shipping
                </div>
            </div>
        </div>
    </div>
</footer>
