@extends('layouts.app')

@section('title', 'My Wishlist')
@section('description', 'Your saved items.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <h1 class="text-3xl md:text-4xl font-light mb-8">My <span class="font-bold">Wishlist</span></h1>
    
    @php
        $wishlistItems = session('wishlist', range(1, 4));
    @endphp
    
    @if (count($wishlistItems) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach ($wishlistItems as $i)
                <div class="product-item bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                    <div class="relative overflow-hidden bg-gray-100">
                        <div class="aspect-[4/5] flex items-center justify-center text-gray-400 text-sm">
                            Wishlist Item {{ $i }}
                        </div>
                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            <button class="wishlist-btn w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-lg hover:bg-gray-900 hover:text-white transition active" data-product-id="wish-{{ $i }}" onclick="toggleWishlist('wish-{{ $i }}')">
                                ❤️
                            </button>
                            <button class="quick-view-btn w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-lg hover:bg-gray-900 hover:text-white transition" data-product-id="wish-{{ $i }}">
                                👁️
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold">Wishlist Item {{ $i }}</h3>
                        <div class="text-lg font-bold mt-1">${{ 40 + ($i * 15) }}</div>
                        <button class="add-to-cart w-full mt-3 py-2 border-2 border-gray-900 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition flex items-center justify-center gap-2" data-product-id="wish-{{ $i }}">
                            🛍️ Add to Cart
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty Wishlist -->
        <div class="text-center py-16">
            <div class="text-6xl mb-6">🤍</div>
            <h2 class="text-2xl font-light">Your wishlist is empty</h2>
            <p class="text-gray-600 mt-2">Start saving your favorite items!</p>
            <a href="/collections/all" class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Explore Products
            </a>
        </div>
    @endif
</div>
@endsection