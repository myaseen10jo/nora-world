{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- Product Image Gallery with Lightbox --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

@props(['images', 'name', 'imagePath'])

@php
    $imageList = collect($images)->map(function($img) use ($imagePath) {
        return [
            'full' => $imagePath($img->path),
            'alt' => $img->alt_text ?? '',
        ];
    })->values()->toArray();

    $isEmpty = empty($imageList);
@endphp

<div
    x-data="productGallery({{ json_encode($imageList) }})"
    x-init="init()"
    class="gallery-container"
>
    {{-- Main Image --}}
    <div
        class="aspect-square bg-stone-50 rounded-2xl overflow-hidden mb-4 relative group cursor-zoom-in"
        @click="openLightbox(currentIndex)"
    >
        @if(!$isEmpty)
            <img
                :src="images[currentIndex].full"
                :alt="images[currentIndex].alt || '{{ addslashes($name) }}'"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                loading="eager"
            >
            {{-- Zoom Icon Overlay --}}
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="bg-black/40 backdrop-blur-sm text-white rounded-full p-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                    </svg>
                </div>
            </div>
            {{-- Image Counter --}}
            @if(count($imageList) > 1)
            <div class="absolute bottom-3 right-3 bg-black/40 backdrop-blur-sm text-white text-xs font-medium px-2.5 py-1 rounded-full">
                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
            </div>
            @endif
        @else
            <div class="w-full h-full flex items-center justify-center text-6xl">🏺</div>
        @endif
    </div>

    {{-- Thumbnails --}}
    @if(!$isEmpty && count($imageList) > 1)
    <div class="grid grid-cols-4 gap-3">
        @foreach($images as $idx => $image)
        <button
            type="button"
            @click="goTo({{ $idx }})"
            class="aspect-square bg-stone-50 rounded-xl overflow-hidden cursor-pointer transition-all duration-300"
            :class="{
                'ring-2 ring-stone-900 ring-offset-2': currentIndex === {{ $idx }},
                'hover:ring-2 hover:ring-stone-300 hover:ring-offset-1 opacity-70 hover:opacity-100': currentIndex !== {{ $idx }}
            }"
        >
            <img
                src="{{ $imagePath($image->path) }}"
                alt="{{ $image->alt_text }}"
                class="w-full h-full object-cover"
                loading="lazy"
            >
        </button>
        @endforeach
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- LIGHTBOX MODAL --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <template x-teleport="body">
        <div
            x-show="lightboxOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="closeLightbox()"
            @keydown.left.window="prev()"
            @keydown.right.window="next()"
            class="fixed inset-0 z-[100] flex items-center justify-center"
            style="display: none;"
        >
            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-black/95 backdrop-blur-md"
                @click="closeLightbox()"
            ></div>

            {{-- Close Button --}}
            <button
                @click="closeLightbox()"
                class="absolute top-4 right-4 z-50 text-white/70 hover:text-white transition-colors p-2 rounded-full hover:bg-white/10"
                title="Close (Esc)"
            >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Previous Button --}}
            <button
                x-show="images.length > 1"
                @click="prev()"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-50 text-white/60 hover:text-white transition-all p-3 rounded-full hover:bg-white/10"
                title="Previous (←)"
            >
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>

            {{-- Next Button --}}
            <button
                x-show="images.length > 1"
                @click="next()"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-50 text-white/60 hover:text-white transition-all p-3 rounded-full hover:bg-white/10"
                title="Next (→)"
            >
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            {{-- Image Container with Zoom --}}
            <div
                class="relative z-10 w-full h-full flex items-center justify-center p-8 md:p-16"
                @dblclick.prevent="toggleZoom($event)"
            >
                <div
                    class="relative transition-transform duration-300 ease-out"
                    :style="zoomStyle"
                    @click.stop="toggleZoom($event)"
                >
                    <img
                        :src="images[currentIndex].full"
                        :alt="images[currentIndex].alt"
                        class="max-h-[85vh] max-w-full object-contain select-none"
                        :class="{ 'cursor-zoom-in': !zoomed, 'cursor-zoom-out': zoomed }"
                        draggable="false"
                    >
                </div>
            </div>

            {{-- Bottom Bar: Thumbnails + Info --}}
            <div class="absolute bottom-0 left-0 right-0 z-50">
                {{-- Thumbnail Strip --}}
                <div x-show="images.length > 1" class="flex justify-center pb-4 px-4">
                    <div class="flex gap-2 bg-black/40 backdrop-blur-md rounded-xl p-2">
                        <template x-for="(img, idx) in images" :key="idx">
                            <button
                                @click="goTo(idx)"
                                class="w-12 h-12 md:w-14 md:h-14 rounded-lg overflow-hidden flex-shrink-0 transition-all duration-200"
                                :class="{
                                    'ring-2 ring-white opacity-100 scale-105': currentIndex === idx,
                                    'opacity-50 hover:opacity-80': currentIndex !== idx
                                }"
                            >
                                <img :src="img.full" :alt="img.alt" class="w-full h-full object-cover" loading="lazy">
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Image Info --}}
                <div class="bg-gradient-to-t from-black/60 to-transparent pb-4 pt-12 px-6">
                    <div class="max-w-3xl mx-auto flex items-center justify-between">
                        <div class="text-white/80 text-sm">
                            <span class="font-medium text-white" x-text="images[currentIndex].alt || '{{ addslashes($name) }}'"></span>
                            <span class="mx-2 opacity-40">·</span>
                            <span x-text="(currentIndex + 1) + ' of ' + images.length"></span>
                        </div>
                        <div class="flex items-center gap-2 text-white/50 text-xs">
                            <span class="hidden md:inline">Double-click to zoom</span>
                            <span class="hidden md:inline opacity-30">·</span>
                            <span class="hidden md:inline">← → navigate</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
function productGallery(images) {
    return {
        images,
        currentIndex: 0,
        lightboxOpen: false,
        zoomed: false,
        zoomLevel: 1,
        zoomX: 0,
        zoomY: 0,
        lastTap: 0,

        init() {
            // Touch swipe support for main image
            this.initSwipe(this.$el.querySelector('.aspect-square'));
        },

        openLightbox(index) {
            this.currentIndex = index;
            this.lightboxOpen = true;
            this.zoomed = false;
            this.zoomLevel = 1;
            this.zoomX = 0;
            this.zoomY = 0;
            document.body.style.overflow = 'hidden';
        },

        closeLightbox() {
            this.lightboxOpen = false;
            this.zoomed = false;
            this.zoomLevel = 1;
            document.body.style.overflow = '';
        },

        goTo(index) {
            this.currentIndex = index;
            this.zoomed = false;
            this.zoomLevel = 1;
            this.zoomX = 0;
            this.zoomY = 0;
        },

        next() {
            if (this.zoomed) {
                this.zoomX -= 50;
                return;
            }
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
            this.zoomed = false;
            this.zoomLevel = 1;
        },

        prev() {
            if (this.zoomed) {
                this.zoomX += 50;
                return;
            }
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            this.zoomed = false;
            this.zoomLevel = 1;
        },

        toggleZoom(event) {
            if (this.zoomed) {
                this.zoomed = false;
                this.zoomLevel = 1;
                this.zoomX = 0;
                this.zoomY = 0;
            } else {
                this.zoomed = true;
                this.zoomLevel = 2.5;

                // Calculate zoom center from click position
                const rect = event.currentTarget.getBoundingClientRect();
                const x = (event.clientX - rect.left) / rect.width;
                const y = (event.clientY - rect.top) / rect.height;
                this.zoomX = (0.5 - x) * 40;
                this.zoomY = (0.5 - y) * 40;
            }
        },

        get zoomStyle() {
            if (!this.zoomed) {
                return 'transform: scale(1) translate(0, 0)';
            }
            return `transform: scale(${this.zoomLevel}) translate(${this.zoomX}%, ${this.zoomY}%)`;
        },

        // Touch swipe support
        initSwipe(el) {
            if (!el) return;
            let startX = 0;
            let startY = 0;

            el.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }, { passive: true });

            el.addEventListener('touchend', (e) => {
                const diffX = e.changedTouches[0].clientX - startX;
                const diffY = e.changedTouches[0].clientY - startY;

                if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                    if (diffX > 0) this.prev();
                    else this.next();
                }
            }, { passive: true });
        },
    };
}
</script>
@endpush
