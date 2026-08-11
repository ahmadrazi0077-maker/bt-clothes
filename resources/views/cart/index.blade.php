@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-3xl font-light mb-8">Shopping Cart</h1>
    
    @if(isset($cart) && $cart && isset($cart['lines']['edges']) && count($cart['lines']['edges']) > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @foreach($cart['lines']['edges'] as $item)
                @php
                    $node = $item['node'];
                    $merchandise = $node['merchandise'];
                    $product = $merchandise['product'] ?? null;
                    $image = isset($product['images']['edges'][0]['node']['url']) 
                        ? $product['images']['edges'][0]['node']['url'] 
                        : null;
                    $price = $merchandise['price']['amount'] ?? 0;
                    $quantity = $node['quantity'] ?? 1;
                    $lineTotal = $price * $quantity;
                    $productTitle = $product['title'] ?? 'Product';
                @endphp
                
                <div class="flex items-center gap-4 p-4 border-b border-gray-100">
                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">
                        @if($image)
                            <img src="{{ $image }}" alt="{{ $productTitle }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl text-gray-400">👕</div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold">{{ $productTitle }}</h3>
                        <p class="text-sm text-gray-500">Rs. {{ number_format((float)$price, 0) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">Rs. {{ number_format((float)$lineTotal, 0) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6 text-right">
            <a href="{{ route('checkout') }}" class="bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Proceed to Checkout →
            </a>
        </div>
        
    @else
        <!-- Empty Cart -->
        <div class="text-center py-16">
            <div class="text-6xl mb-6">🛒</div>
            <h2 class="text-2xl font-light">Your cart is empty</h2>
            <p class="text-gray-600 mt-2">Looks like you haven't added any items yet.</p>
            <a href="/products" class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection