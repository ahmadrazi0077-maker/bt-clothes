@extends('layouts.app')

@section('title', 'All Categories')
@section('description', 'Browse all our product categories.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-5xl font-light">All <span class="font-bold">Categories</span></h1>
        <p class="text-gray-600 mt-3 max-w-2xl mx-auto">
            Explore our complete collection of categories
        </p>
    </div>
    
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        @forelse($categories ?? [] as $category)
            <a href="/collections/{{ $category['handle'] }}" 
               class="category-card group relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br {{ $category['color'] ?? 'from-gray-100 to-gray-200' }} shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full -mr-10 -mt-10"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/20 rounded-full -ml-8 -mb-8"></div>
                
                <div class="relative z-10">
                    <div class="text-4xl md:text-5xl mb-3 transform group-hover:scale-110 transition-transform duration-300">
                        {{ $category['icon'] ?? '📦' }}
                    </div>
                    <h3 class="font-semibold text-gray-800 text-base md:text-lg">
                        {{ $category['name'] }}
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $category['count'] ?? 0 }} Products
                    </p>
                    <div class="mt-3 inline-flex items-center text-sm font-medium text-gray-700 group-hover:text-gray-900">
                        Shop Now →
                    </div>
                </div>
                
                <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
            </a>
        @empty
            @for ($i = 1; $i <= 8; $i++)
                <div class="bg-gray-100 rounded-2xl p-6 text-center">
                    <div class="text-4xl mb-3">📦</div>
                    <h3 class="font-semibold">Category {{ $i }}</h3>
                    <p class="text-sm text-gray-500">0 Products</p>
                </div>
            @endfor
        @endforelse
    </div>
</div>
@endsection