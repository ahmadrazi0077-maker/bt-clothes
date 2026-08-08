@extends('layouts.app')

@section('title', 'All Collections')
@section('description', 'Browse all our collections.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-5xl font-light">Our <span class="font-bold">Collections</span></h1>
        <p class="text-gray-600 mt-3 max-w-2xl mx-auto">
            Explore our curated collections featuring the finest pieces
        </p>
    </div>
    
    @if($collections && count($collections) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($collections as $collection)
                <div class="collection-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                    <!-- Collection Image -->
                    <div class="relative h-48 md:h-56 overflow-hidden bg-gray-100">
                        @if($collection['image'] ?? false)
                            <img src="{{ $collection['image'] }}" 
                                 alt="{{ $collection['title'] }}"
                                 class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-6xl text-gray-300">
                                {{ $collection['icon'] ?? '📦' }}
                            </div>
                        @endif
                        
                        <!-- Product Count Badge -->
                        <div class="absolute bottom-3 right-3 bg-black/70 text-white text-xs px-3 py-1 rounded-full">
                            {{ $collection['productCount'] ?? 0 }} Products
                        </div>
                    </div>
                    
                    <!-- Collection Info -->
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">{{ $collection['icon'] ?? '📦' }}</span>
                            <h3 class="font-semibold text-lg">{{ $collection['title'] }}</h3>
                        </div>
                        
                        <p class="text-gray-600 text-sm line-clamp-2">
                            {{ $collection['description'] ?? 'Explore our collection' }}
                        </p>
                        
                        <a href="/collections/{{ $collection['handle'] }}" 
                           class="inline-flex items-center gap-2 mt-4 text-gray-900 font-semibold hover:text-gray-600 transition group">
                            Shop Collection
                            <span class="transform group-hover:translate-x-1 transition">→</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- No Collections Message -->
        <div class="text-center py-16">
            <div class="text-6xl mb-6">📦</div>
            <h2 class="text-2xl font-light">No Collections Found</h2>
            <p class="text-gray-600 mt-2">Please add collections in your Shopify store.</p>
            <a href="https://{{ config('shopify.store') }}/admin/collections" 
               target="_blank" 
               class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Add Collections in Shopify
            </a>
        </div>
    @endif
</div>
@endsection