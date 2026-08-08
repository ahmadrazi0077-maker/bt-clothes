@extends('layouts.app')

@section('title', $post['title'] ?? 'Blog Post')
@section('description', strip_tags($post['excerpt'] ?? ''))

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    @if(isset($post) && $post)
        <article class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-3 text-sm text-gray-500 mb-4">
                    <span>{{ \Carbon\Carbon::parse($post['publishedAt'])->format('M d, Y') }}</span>
                    <span>•</span>
                    <span>By {{ $post['author'] }}</span>
                    @if($post['tags'])
                        <span class="bg-gray-100 px-3 py-1 rounded-full text-xs">#{{ $post['tags'][0] }}</span>
                    @endif
                </div>
                <h1 class="text-3xl md:text-5xl font-light leading-tight">
                    {{ $post['title'] }}
                </h1>
            </div>
            
            <!-- Featured Image -->
            @if($post['image'])
                <div class="rounded-2xl overflow-hidden mb-8">
                    <img src="{{ $post['image'] }}" 
                         alt="{{ $post['imageAlt'] ?? $post['title'] }}"
                         class="w-full h-64 md:h-96 object-cover">
                </div>
            @endif
            
            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                {!! $post['contentHtml'] ?? $post['content'] !!}
            </div>
            
            <!-- Tags -->
            @if($post['tags'] && count($post['tags']) > 0)
                <div class="border-t border-gray-200 mt-8 pt-8">
                    <div class="flex flex-wrap gap-2">
                        @foreach($post['tags'] as $tag)
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-600">#{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Back to Blog -->
            <div class="border-t border-gray-200 mt-8 pt-8">
                <a href="/blog" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition">
                    ← Back to Journal
                </a>
            </div>
        </article>
        
        <!-- Related Posts -->
        @if(isset($related) && count($related) > 0)
            <section class="mt-16 pt-8 border-t border-gray-200">
                <h2 class="text-2xl font-light text-center mb-8">You May <span class="font-bold">Also Like</span></h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related as $post)
                        <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            @if($post['image'])
                                <div class="h-40 overflow-hidden">
                                    <img src="{{ $post['image'] }}" 
                                         alt="{{ $post['title'] }}"
                                         class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="p-4">
                                <h4 class="font-semibold text-sm line-clamp-2">
                                    <a href="/blog/{{ $post['handle'] }}" class="hover:text-gray-600 transition">
                                        {{ $post['title'] }}
                                    </a>
                                </h4>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($post['publishedAt'])->format('M d, Y') }}
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    @else
        <div class="text-center py-16">
            <div class="text-6xl mb-6">🔍</div>
            <h2 class="text-2xl font-light">Post Not Found</h2>
            <p class="text-gray-600 mt-2">The blog post you're looking for doesn't exist.</p>
            <a href="/blog" class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Back to Journal
            </a>
        </div>
    @endif
</div>
@endsection