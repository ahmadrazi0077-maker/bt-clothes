@extends('layouts.app')

@section('title', 'About Us - BT Clothes | Ready Made Wear')
@section('description', 'Discover BT Clothes - your trusted destination for premium ready made wear. Quality fashion for every occasion.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    
    <!-- Hero Section -->
    <div class="text-center mb-16">
        <span class="text-sm text-gray-500 uppercase tracking-wider font-semibold">Our Story</span>
        <h1 class="text-4xl md:text-6xl font-light mt-2">
            About <span class="font-bold">BT Clothes</span>
        </h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto mt-4">
            Your trusted destination for premium ready made wear. Quality fashion for every occasion.
        </p>
    </div>

    <!-- Mission & Vision -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
        <div>
            <h2 class="text-3xl font-light mb-4">Our <span class="font-bold">Mission</span></h2>
            <p class="text-gray-600 leading-relaxed">
                At <strong>BT Clothes</strong>, we believe that fashion should be accessible, comfortable, and sustainable. Our mission is to provide high-quality ready made wear that combines timeless style with modern comfort. Every piece is crafted with care, ensuring you look and feel your best.
            </p>
            <p class="text-gray-600 leading-relaxed mt-4">
                We are committed to ethical production practices and using materials that are kind to both you and the planet. Join us in redefining everyday fashion.
            </p>
            <div class="mt-6 flex flex-wrap gap-4">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="text-green-500">✓</span> Premium Quality
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="text-green-500">✓</span> Sustainable Materials
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="text-green-500">✓</span> Ethical Production
                </div>
            </div>
        </div>
        <div class="relative">
            <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=600&h=400&fit=crop" 
                 alt="BT Clothes - Ready Made Wear"
                 class="w-full h-64 md:h-80 object-cover rounded-2xl shadow-lg"
                 loading="lazy">
            <div class="absolute -bottom-4 -right-4 bg-gray-900 text-white text-sm px-4 py-2 rounded-lg shadow-lg">
                🌟 Since 2024
            </div>
        </div>
    </div>

    <!-- Values -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <div class="bg-white rounded-xl shadow-sm p-8 text-center hover:shadow-xl transition">
            <div class="text-4xl mb-4">✨</div>
            <h3 class="font-semibold text-xl">Quality First</h3>
            <p class="text-gray-600 text-sm mt-2">We source the finest materials to ensure every garment meets our high standards.</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-8 text-center hover:shadow-xl transition">
            <div class="text-4xl mb-4">🌿</div>
            <h3 class="font-semibold text-xl">Sustainable Fashion</h3>
            <p class="text-gray-600 text-sm mt-2">Our commitment to eco-friendly practices means you can shop with a clear conscience.</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-8 text-center hover:shadow-xl transition">
            <div class="text-4xl mb-4">❤️</div>
            <h3 class="font-semibold text-xl">Customer Care</h3>
            <p class="text-gray-600 text-sm mt-2">Your satisfaction is our priority. We're here to ensure you love every purchase.</p>
        </div>
    </div>

    <!-- Team Section -->
    

    <!-- CTA Section -->
    <div class="bg-gray-900 text-white rounded-2xl p-8 md:p-12 text-center">
        <h2 class="text-2xl md:text-3xl font-light mb-4">
            Ready to <span class="font-bold">Elevate Your Wardrobe</span>?
        </h2>
        <p class="text-gray-300 max-w-xl mx-auto mb-6">
            Explore our collection of premium ready made wear and find your perfect fit today.
        </p>
        <a href="/collections/all" class="inline-block bg-white text-gray-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
            Shop Now →
        </a>
    </div>
</div>
@endsection