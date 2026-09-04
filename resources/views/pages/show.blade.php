@extends('layouts.app')

@section('title', $page->title . ' - عالم نورا للكنوز')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-olive-500">Home</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800">{{ $page->title }}</span>
    </nav>

    <article class="bg-white rounded-lg p-8 shadow-sm">
        <h1 class="text-3xl font-serif font-bold text-gray-800 mb-8">{{ $page->title }}</h1>
        
        <div class="prose prose-lg max-w-none text-gray-600">
            {!! $page->content !!}
        </div>
    </article>
</div>
@endsection
