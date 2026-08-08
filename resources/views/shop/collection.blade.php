@extends('layouts.app')

@section('title', $collection['title'] ?? 'Collection')
@section('description', $collection['description'] ?? '')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    @if($collection)
        <!-- Collection Header -->
        <div class="relative rounded-2xl overflow-hidden mb-12">
            @if($collection['image'] ?? false)
                <div class="h-64 md:h-80 w-full">
                    <img src="{{ $collection['image'] }}" 
                         alt="{{ $collection['title'] }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent flex items-end">
                    <div class="container mx-auto px-4 pb-8">
                        <h1 class="text-3xl md:text-5xl font-light text-white">
                            {{ $collection['title'] }}
                        </h1>
                        <p class="text-white/80 text-lg mt-2 max-w-2xl">
                            {{ $collection['description'] ?? '' }}
                        </p>
                        <div class="flex items-center gap-4 mt-4 text-white/70 text-sm">
                            <span>🛍️ {{ $collection['productCount'] ?? 0 }} Products</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-r from-gray-100 to-gray-200 p-12 text-center">
                    <span class="text-6xl block mb-4">{{ $collection['icon'] ?? '📦' }}</span>
                    <h1 class="text-3xl md:text-5xl font-light">{{ $collection['title'] }}</h1>
                    <p class="text-gray-600 mt-2">{{ $collection['description'] ?? '' }}</p>
                    <div class="text-sm text-gray-500 mt-2">
                        🛍️ {{ $collection['productCount'] ?? 0 }} Products
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Products Grid -->
        @if($products && count($products) > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($products as $product)
                    <div class="product-item bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                        <div class="relative overflow-hidden bg-gray-100">
                            @if($product['image'] ?? false)
                                <img src="{{ $product['image'] }}" 
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
                            
                            @if($product['comparePrice'] && $product['comparePrice'] > $product['price'])
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs px-3 py-1 rounded-full font-bold uppercase">
                                    Sale
                                </span>
                            @endif
                            
                            @if(!$product['availableForSale'])
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg px-4 py-2 bg-black/70 rounded-lg">
                                        Sold Out
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            @if($product['vendor'])
                                <div class="text-xs text-gray-500 uppercase tracking-wider">{{ $product['vendor'] }}</div>
                            @endif
                            
                            <h3 class="font-semibold mt-1">
                                <a href="/product/{{ $product['handle'] }}" class="hover:text-gray-600 transition line-clamp-2">
                                    {{ $product['title'] }}
                                </a>
                            </h3>
                            
                            <div class="flex items-center gap-2 mt-2">
                                <span class="font-bold text-lg">
                                    ${{ number_format((float)$product['price'], 2) }}
                                </span>
                                @if($product['comparePrice'] && $product['comparePrice'] > $product['price'])
                                    <span class="text-gray-400 line-through text-sm">
                                        ${{ number_format((float)$product['comparePrice'], 2) }}
                                    </span>
                                @endif
                            </div>
                            
                            <button class="add-to-cart w-full mt-3 py-2 border-2 border-gray-900 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition flex items-center justify-center gap-2
                                    @if(!$product['availableForSale']) opacity-50 cursor-not-allowed @endif"
                                    data-product-id="{{ $product['id'] }}"
                                    data-variant-id="{{ $product['variantId'] }}"
                                    @if(!$product['availableForSale']) disabled @endif>
                                🛍️ 
                                @if($product['availableForSale'])
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
            <!-- No Products in Collection -->
            <div class="text-center py-16">
                <div class="text-6xl mb-6">🔍</div>
                <h2 class="text-2xl font-light">No Products in this Collection</h2>
                <p class="text-gray-600 mt-2">Please add products to this collection in your Shopify store.</p>
                <a href="https://{{ config('shopify.store') }}/admin/collections/{{ $collection['id'] }}" 
                   target="_blank" 
                   class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                    Add Products in Shopify
                </a>
            </div>
        @endif
    @else
        <!-- Collection Not Found -->
        <div class="text-center py-16">
            <div class="text-6xl mb-6">🔍</div>
            <h2 class="text-2xl font-light">Collection Not Found</h2>
            <p class="text-gray-600 mt-2">The collection you're looking for doesn't exist.</p>
            <a href="/collections" class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                View All Collections
            </a>
        </div>
    @endif
</div>
@endsection