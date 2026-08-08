@extends('layouts.app')

@section('title', 'Search Results')
@section('description', 'Search results for your query.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <!-- Search Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-light">Search <span class="font-bold">Results</span></h1>
            @if(isset($query) && $query)
                <p class="text-gray-600 mt-1">
                    {{ $total ?? 0 }} results for "<strong>{{ $query }}</strong>"
                </p>
            @endif
        </div>
        
        <!-- Search Form -->
        <form action="/search" method="GET" class="flex w-full md:w-auto">
            <input type="search" 
                   name="q" 
                   value="{{ $query ?? '' }}"
                   placeholder="Search products..." 
                   class="flex-1 md:w-64 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-r-lg hover:bg-gray-700 transition">
                🔍
            </button>
        </form>
    </div>
    
    <!-- Results -->
    @if(isset($query) && $query)
        @if(isset($products) && count($products) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($products as $product)
                    <div class="product-item bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                        <div class="relative overflow-hidden bg-gray-100">
                            @if(isset($product['images']['edges'][0]['node']['url']))
                                <img src="{{ $product['images']['edges'][0]['node']['url'] }}" 
                                     alt="{{ $product['title'] }}"
                                     class="w-full aspect-[4/5] object-cover transition-transform duration-600 group-hover:scale-105"
                                     loading="lazy">
                            @else
                                <div class="w-full aspect-[4/5] flex items-center justify-center text-4xl text-gray-400">
                                    👕
                                </div>
                            @endif
                            
                            <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <button class="wishlist-btn w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-lg hover:bg-gray-900 hover:text-white transition" 
                                        data-product-id="{{ $product['id'] }}" 
                                        onclick="toggleWishlist('{{ $product['id'] }}')">
                                    🤍
                                </button>
                                <button class="quick-view-btn w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-lg hover:bg-gray-900 hover:text-white transition" 
                                        data-product-id="{{ $product['id'] }}">
                                    👁️
                                </button>
                            </div>
                            
                            @php
                                $price = $product['priceRange']['minVariantPrice']['amount'] ?? '0.00';
                                $comparePrice = $product['compareAtPriceRange']['minVariantPrice']['amount'] ?? null;
                            @endphp
                            
                            @if($comparePrice && $comparePrice > $price)
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs px-3 py-1 rounded-full font-bold uppercase">
                                    Sale
                                </span>
                            @endif
                            
                            @if(!($product['availableForSale'] ?? true))
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg px-4 py-2 bg-black/70 rounded-lg">
                                        Sold Out
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            @if(isset($product['vendor']))
                                <div class="text-xs text-gray-500 uppercase tracking-wider">{{ $product['vendor'] }}</div>
                            @endif
                            
                            <h3 class="font-semibold mt-1">
                                <a href="/product/{{ $product['handle'] }}" class="hover:text-gray-600 transition line-clamp-2">
                                    {{ $product['title'] }}
                                </a>
                            </h3>
                            
                            <div class="flex items-center gap-2 mt-2">
                                <span class="font-bold text-lg">
                                    ${{ number_format((float)$price, 2) }}
                                </span>
                                @if($comparePrice && $comparePrice > $price)
                                    <span class="text-gray-400 line-through text-sm">
                                        ${{ number_format((float)$comparePrice, 2) }}
                                    </span>
                                @endif
                            </div>
                            
                            @php
                                $variantId = $product['variants']['edges'][0]['node']['id'] ?? null;
                                $available = $product['availableForSale'] ?? true;
                            @endphp
                            
                            <button class="add-to-cart w-full mt-3 py-2 border-2 border-gray-900 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition flex items-center justify-center gap-2
                                    @if(!$available) opacity-50 cursor-not-allowed @endif"
                                    data-product-id="{{ $product['id'] }}"
                                    data-variant-id="{{ $variantId }}"
                                    @if(!$available) disabled @endif>
                                🛍️ 
                                @if($available)
                                    Add to Cart
                                @else
                                    Sold Out
                                @endif
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-16">
                <div class="text-6xl mb-6">🔍</div>
                <h2 class="text-2xl font-light">No results found</h2>
                <p class="text-gray-600 mt-2">We couldn't find any products matching "<strong>{{ $query }}</strong>".</p>
                <p class="text-gray-500 text-sm mt-1">Try different keywords or browse our collections.</p>
                <div class="flex flex-wrap justify-center gap-4 mt-6">
                    <a href="/collections/all" class="inline-block bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                        Browse All Products
                    </a>
                    <a href="/categories" class="inline-block border-2 border-gray-900 text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition">
                        View Categories
                    </a>
                </div>
            </div>
        @endif
    @else
        <!-- Empty Search -->
        <div class="text-center py-16">
            <div class="text-6xl mb-6">🔍</div>
            <h2 class="text-2xl font-light">Search for products</h2>
            <p class="text-gray-600 mt-2">Type something in the search box above to find products.</p>
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <a href="/collections/all" class="inline-block bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                    Browse All Products
                </a>
            </div>
        </div>
    @endif
</div>
@endsection