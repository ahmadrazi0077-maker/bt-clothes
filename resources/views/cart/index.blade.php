@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>
    
    {{-- ✅ DEBUG INFO --}}
    <div class="bg-yellow-100 p-4 mb-4 rounded">
        <p><strong>Debug:</strong> {{ $debug ?? 'No debug' }}</p>
        <p><strong>Session Cart ID:</strong> {{ session('shopify_cart_id') ?? 'None' }}</p>
        <p><strong>Items Count:</strong> {{ count($items ?? []) }}</p>
        <p><strong>Cart Total:</strong> Rs. {{ number_format($total ?? 0, 0) }}</p>
    </div>
    
    @if(isset($items) && count($items) > 0)
        @foreach($items as $item)
            @php
                $node = $item['node'];
                $merchandise = $node['merchandise'];
                $product = $merchandise['product'] ?? null;
                $price = $merchandise['price']['amount'] ?? 0;
                $qty = $node['quantity'] ?? 1;
                $lineTotal = $price * $qty;
                $title = $product['title'] ?? 'Product';
                $image = $product['images']['edges'][0]['node']['url'] ?? null;
            @endphp
            
            <div class="flex items-center gap-4 border-b py-4" data-line-id="{{ $node['id'] }}">
                <div class="w-16 h-16 bg-gray-100 rounded">
                    @if($image)
                        <img src="{{ $image }}" class="w-full h-full object-cover rounded">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-2xl">👕</div>
                    @endif
                </div>
                
                <div class="flex-1">
                    <h3 class="font-semibold">{{ $title }}</h3>
                    <button class="remove-item text-sm text-red-500" data-line-id="{{ $node['id'] }}">Remove</button>
                </div>
                
                <div>Rs. {{ number_format($price, 0) }}</div>
                
                <div class="flex border rounded">
                    <button class="qty-dec px-3 py-1 bg-gray-50" data-line-id="{{ $node['id'] }}">−</button>
                    <input type="text" class="qty-input w-12 text-center border-0" value="{{ $qty }}" readonly data-line-id="{{ $node['id'] }}">
                    <button class="qty-inc px-3 py-1 bg-gray-50" data-line-id="{{ $node['id'] }}">+</button>
                </div>
                
                <div class="font-bold min-w-[80px] text-right">Rs. {{ number_format($lineTotal, 0) }}</div>
            </div>
        @endforeach
        
        <div class="mt-6 text-right">
            <p class="text-xl font-bold">Total: Rs. {{ number_format($total ?? 0, 0) }}</p>
            <a href="{{ route('checkout') }}" class="mt-4 inline-block bg-gray-900 text-white px-6 py-3 rounded-lg hover:bg-gray-700">
                Checkout →
            </a>
        </div>
    @else
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🛒</div>
            <h2 class="text-xl">Cart is empty</h2>
            <a href="/" class="mt-4 inline-block bg-gray-900 text-white px-6 py-2 rounded-lg hover:bg-gray-700">
                Shop Now
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart page loaded');
    
    // Quantity
    document.querySelectorAll('.qty-dec, .qty-inc').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('[data-line-id]');
            const input = row.querySelector('.qty-input');
            const lineId = row.dataset.lineId;
            let qty = parseInt(input.value) || 1;
            
            if (this.classList.contains('qty-dec')) {
                if (qty > 1) qty--;
            } else {
                if (qty < 10) qty++;
            }
            
            input.value = qty;
            
            fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({line_id: lineId, quantity: qty})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
            });
        });
    });
    
    // Remove
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Remove item?')) return;
            const lineId = this.dataset.lineId;
            fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({line_id: lineId})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) location.reload();
            });
        });
    });
});
</script>
@endsection