@extends('layouts.app')

@section('title', 'Collections - عالم نورا للكنوز')

@section('content')
<div class="pt-24 pb-16">
    {{-- Header --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <p class="text-[11px] text-stone-400 uppercase tracking-[0.25em] mb-3 font-medium">Curated for You</p>
        <h1 class="text-3xl md:text-4xl font-serif font-bold text-stone-900 tracking-tight mb-2">Collections</h1>
        <p class="text-sm text-stone-500">Thoughtfully assembled collections of treasures</p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($collections as $collection)
            <a href="{{ route('collections.show', $collection->slug) }}" class="group">
                <div class="bg-white rounded-2xl overflow-hidden border border-stone-100 hover:border-stone-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
                    <div class="aspect-video bg-stone-50 flex items-center justify-center overflow-hidden">
                        @if($collection->image)
                        <img src="{{ asset($collection->image) }}" alt="{{ $collection->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                        <div class="text-4xl opacity-40">📚</div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h2 class="text-lg font-serif font-bold text-stone-800 group-hover:text-stone-600 transition-colors mb-1">{{ $collection->name }}</h2>
                        <p class="text-xs text-stone-400 mb-3">{{ $collection->products_count }} {{ Str::plural('item', $collection->products_count) }}</p>
                        @if($collection->description)
                        <p class="text-sm text-stone-500 line-clamp-2">{{ $collection->description }}</p>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
