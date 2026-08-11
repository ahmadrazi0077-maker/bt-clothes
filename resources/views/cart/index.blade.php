@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl md:text-4xl font-light">Shopping <span class="font-bold">Cart</span></h1>
        <a href="https://shop.btclothes.com/cart" target="_blank" class="text-sm text-gray-500 hover:text-gray-900 transition">
            View on Shopify →
        </a>
    </div>
    
    <!-- ✅ Shopify Cart Iframe -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <iframe 
            src="https://shop.btclothes.com/cart" 
            class="w-full min-h-[500px] md:min-h-[600px] border-0"
            loading="lazy"
        ></iframe>
    </div>
    
    <div class="mt-6 text-center text-sm text-gray-500">
        🔒 Secure checkout powered by Shopify
    </div>
</div>
@endsection