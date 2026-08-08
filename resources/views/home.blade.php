@extends('layouts.app')

@section('title', 'BT Clothes - Timeless Clothing')
@section('description', 'Discover our collection of timeless, minimalist clothing.')

@section('content')
    <!-- Hero Section -->
    @include('components.hero-slideshow')
    
    <!-- Categories Section -->
    @if(isset($categories) && count($categories) > 0)
        @include('components.categories', ['categories' => $categories])
    @endif
    
    <!-- Featured Products -->
    <section class="py-16 md:py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <span class="text-sm text-gray-500 uppercase tracking-wider">Our Collection</span>
                <h2 class="text-3xl md:text-4xl font-light mt-2">
                    Featured <span class="font-bold">Products</span>
                </h2>
                <p class="text-gray-600 mt-3">Handpicked for their texture, tone, and timelessness.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @forelse($featured_products ?? [] as $product)
                    @include('components.product-card', ['product' => $product])
                @empty
                    @for ($i = 1; $i <= 8; $i++)
                        <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                            <div class="bg-gray-100 h-48 rounded-lg mb-4 flex items-center justify-center text-gray-400 text-4xl">
                                👕
                            </div>
                            <h3 class="font-semibold">Product {{ $i }}</h3>
                            <div class="font-bold mt-1">${{ number_format(50 * $i, 2) }}</div>
                            <button class="w-full mt-3 py-2 border-2 border-gray-900 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition">
                                🛍️ Add to Cart
                            </button>
                        </div>
                    @endfor
                @endforelse
            </div>
            
            <div class="text-center mt-12">
                <a href="/products" class="inline-block px-8 py-3 border-2 border-gray-900 rounded font-semibold uppercase text-sm tracking-wider hover:bg-gray-900 hover:text-white transition">
                    View All Products
                </a>
            </div>
        </div>
    </section>
    
    <!-- ✅ Why Choose Us -->
    @include('components.why-choose-us')
    
    <!-- Newsletter -->
    @include('components.newsletter')
@endsection