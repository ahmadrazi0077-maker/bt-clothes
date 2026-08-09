@extends('layouts.app')

@section('title', 'All Products')
@section('description', 'Browse our collection of products.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-light">All <span class="font-bold">Products</span></h1>
            <p class="text-gray-600 mt-1">
                @if(isset($products) && count($products) > 0)
                    {{ count($products) }} products found
                @else
                    No products found
                @endif
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            <label class="text-sm text-gray-600">Sort by:</label>
            <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900">
                <option>Featured</option>
                <option>Price: Low to High</option>
                <option>Price: High to Low</option>
                <option>Newest First</option>
            </select>
        </div>
    </div>
    
    @if(isset($products) && count($products) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
        
        <div class="flex justify-center gap-2 mt-12">
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">Previous</button>
            <button class="px-4 py-2 bg-gray-900 text-white rounded-lg font-semibold">1</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">2</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">3</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">Next</button>
        </div>
    @else
        <div class="text-center py-16">
            <div class="text-6xl mb-6">🔍</div>
            <h2 class="text-2xl font-light">No Products Found</h2>
            <p class="text-gray-600 mt-2">Please add products in your Shopify store.</p>
            <a href="https://{{ config('shopify.store') }}/admin/products" 
               target="_blank" 
               class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Add Products in Shopify
            </a>
        </div>
    @endif
</div>
@endsection