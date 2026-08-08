@extends('layouts.app')

@section('title', 'Journal')
@section('description', 'Read our latest articles and style inspiration.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="text-center mb-12">
        <span class="text-sm text-gray-500 uppercase tracking-wider">Our Journal</span>
        <h1 class="text-3xl md:text-5xl font-light mt-2">
            Latest <span class="font-bold">Articles</span>
        </h1>
        <p class="text-gray-600 mt-3 max-w-2xl mx-auto">
            Style inspiration, sustainability tips, and behind-the-scenes stories.
        </p>
    </div>
    
    @if(isset($posts) && count($posts) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($posts as $post)
                <article class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-xl transition-all duration-500 hover:-translate-y-2 group">
                    @if($post['image'])
                        <div class="h-56 overflow-hidden">
                            <img src="{{ $post['image'] }}" 
                                 alt="{{ $post['imageAlt'] ?? $post['title'] }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    @else
                        <div class="h-56 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-4xl text-gray-400">
                            📝
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                            <span>{{ \Carbon\Carbon::parse($post['publishedAt'])->format('M d, Y') }}</span>
                            @if($post['tags'])
                                <span class="bg-gray-100 px-2 py-0.5 rounded">#{{ $post['tags'][0] }}</span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-xl">
                            <a href="/blog/{{ $post['handle'] }}" class="hover:text-gray-600 transition line-clamp-2">
                                {{ $post['title'] }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mt-2 line-clamp-3">
                            {{ $post['excerpt'] ?? strip_tags($post['content']) }}
                        </p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-xs text-gray-500">By {{ $post['author'] }}</span>
                            <a href="/blog/{{ $post['handle'] }}" class="text-sm font-semibold text-gray-900 hover:text-gray-600 transition">
                                Read More →
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="text-center py-16">
            <div class="text-6xl mb-6">📝</div>
            <h2 class="text-2xl font-light">No Blog Posts Found</h2>
            <p class="text-gray-600 mt-2">Please add blog posts in your Shopify store.</p>
            <a href="https://{{ config('shopify.store') }}/admin/blogs" 
               target="_blank" 
               class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Add Blog in Shopify
            </a>
        </div>
    @endif
</div>
@endsection