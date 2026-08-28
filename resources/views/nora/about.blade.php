@extends('layouts.app')

@section('title', 'Our Story — NORA')

@section('content')
<div x-data="scrollReveal()" x-init="init()">

{{-- Hero --}}
<section class="relative overflow-hidden bg-stone-900 text-white min-h-[60vh] flex items-center">
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 256 256%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%274%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>
    <div class="absolute top-20 right-20 w-96 h-96 bg-amber-500/5 rounded-full blur-[120px] hero-float"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 text-center">
        <p class="text-[11px] text-stone-500 uppercase tracking-[0.3em] mb-6 font-medium page-enter" style="animation-delay: 0.1s;">From Our Home to Yours</p>
        <h1 class="text-5xl md:text-7xl font-serif font-bold mb-6 leading-[0.9] page-enter" style="animation-delay: 0.2s;">NORA</h1>
        <p class="text-sm text-stone-400 tracking-[0.3em] uppercase mb-8 page-enter" style="animation-delay: 0.25s;">Vintage · Collectibles · Art · Pre-Loved</p>
        <p class="text-lg md:text-xl text-stone-300/70 italic max-w-2xl mx-auto page-enter" style="animation-delay: 0.35s;">
            "Every piece has lived a story. Now it's ready to begin another."
        </p>
    </div>
</section>

{{-- Introduction --}}
<section class="section-padding bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal-scale">
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-stone-900 mb-4">Welcome to Nora</h2>
            <p class="text-sm text-stone-400 uppercase tracking-[0.2em]">— From Our Home to Yours —</p>
        </div>

        <div class="reveal" style="transition-delay: 0.1s;">
            <p class="text-lg text-stone-600 leading-relaxed mb-10 first-letter:text-5xl first-letter:font-serif first-letter:font-bold first-letter:text-stone-900 first-letter:float-left first-letter:mr-3 first-letter:mt-1">
                Nora is more than a store. It is a carefully gathered collection of meaningful pieces that have lived in homes, witnessed ordinary days and special moments, and been treasured by the people who owned them.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mt-12">
            <div class="bg-stone-50 rounded-2xl p-8 border border-stone-100 reveal" style="transition-delay: 0.15s;">
                <div class="text-2xl mb-4">🏠</div>
                <h3 class="text-lg font-serif font-bold text-stone-900 mb-3">A Gathering of Stories</h3>
                <p class="text-sm text-stone-600 leading-relaxed">
                    Some of our pieces were passed down through the family. Some have been part of our own home for many years, while others were personally chosen simply because they were beautiful, charming, unusual, or capable of bringing joy.
                </p>
            </div>
            <div class="bg-stone-50 rounded-2xl p-8 border border-stone-100 reveal" style="transition-delay: 0.25s;">
                <div class="text-2xl mb-4">✨</div>
                <h3 class="text-lg font-serif font-bold text-stone-900 mb-3">Beyond Categories</h3>
                <p class="text-sm text-stone-600 leading-relaxed">
                    Here you may discover vintage and pre-loved ceramics, glassware, tableware, commemorative and collectible pieces, folk dolls, watches, jewellery, artwork, decorative objects, accessories, handbags, clothing, and other treasures that do not always belong to one category.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Philosophy --}}
<section class="section-padding bg-stone-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.02]" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%270 0 256 256%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cfilter id=%27noise%27%3E%3CfeTurbulence type=%27fractalNoise%27 baseFrequency=%270.9%27 numOctaves=%274%27 stitchTiles=%27stitch%27/%3E%3C/filter%3E%3Crect width=%27100%25%27 height=%27100%25%27 filter=%27url(%23noise)%27/%3E%3C/svg%3E');"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="reveal-scale">
            <p class="text-[11px] text-stone-500 uppercase tracking-[0.3em] mb-8 font-medium">Our Philosophy</p>
            <h2 class="text-3xl md:text-5xl font-serif font-bold mb-10">Our Philosophy</h2>
        </div>

        <blockquote class="text-lg md:text-xl leading-relaxed text-stone-400 italic mb-12 max-w-3xl mx-auto font-light reveal" style="transition-delay: 0.15s;">
            "We believe that the true value of an object is not measured only by its age, maker, material, or price. Sometimes its greatest value lies in the memories it carries, the craftsmanship preserved within it, the culture it represents, or the quiet beauty it can bring into another home."
        </blockquote>

        <div class="grid md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 reveal" style="transition-delay: 0.2s;">
                <div class="text-2xl mb-3">🔍</div>
                <h3 class="font-semibold text-white mb-2 text-sm">Honesty First</h3>
                <p class="text-stone-400 text-xs leading-relaxed">Every piece is presented as honestly and respectfully as possible, using photographs of the actual item and clearly describing its condition.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 reveal" style="transition-delay: 0.3s;">
                <div class="text-2xl mb-3">🕐</div>
                <h3 class="font-semibold text-white mb-2 text-sm">Marks of Time</h3>
                <p class="text-stone-400 text-xs leading-relaxed">We do not hide the marks left by time. A gentle scratch, a faded colour, or another small sign of use may simply be part of the piece's journey.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 reveal" style="transition-delay: 0.4s;">
                <div class="text-2xl mb-3">❤️</div>
                <h3 class="font-semibold text-white mb-2 text-sm">Meaningful Things</h3>
                <p class="text-stone-400 text-xs leading-relaxed">We are offering these pieces because beautiful and meaningful things deserve to be seen, appreciated, and loved again.</p>
            </div>
        </div>
    </div>
</section>

{{-- Closing --}}
<section class="section-padding bg-white">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <div class="reveal-scale">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-stone-900 mb-6">Our Greatest Hope</h2>
            <p class="text-base text-stone-600 leading-relaxed mb-6">
                Every piece finds the right person: someone who will not leave it forgotten, but will display it, use it, cherish it, and allow it to become part of a new story.
            </p>
            <p class="text-sm text-stone-400 leading-relaxed mb-10">
                Thank you for visiting Nora and for giving a piece with a past the chance to have a future.
            </p>
        </div>

        <div class="mt-12 reveal" style="transition-delay: 0.2s;">
            <a href="{{ route('nora.gallery') }}" class="btn-primary inline-flex items-center gap-2.5">
                Explore Our Collection
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</section>

</div>
@endsection
