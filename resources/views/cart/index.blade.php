@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl md:text-4xl font-light">Shopping <span class="font-bold">Cart</span></h1>
        <span class="text-sm text-gray-500" id="cartItemCount">
            {{ $itemCount ?? 0 }} items
        </span>
    </div>
    
    @if(isset($items) && count($items) > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @foreach($items as $item)
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
                    $productHandle = $product['handle'] ?? '#';
                    $variantTitle = $merchandise['title'] ?? '';
                @endphp
                
                <div class="cart-item flex flex-wrap items-center gap-4 p-4 border-b border-gray-100 last:border-0" 
                     data-line-id="{{ $node['id'] }}"
                     data-price="{{ $price }}">
                    
                    <!-- Product Image -->
                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">
                        @if($image)
                            <img src="{{ $image }}" alt="{{ $productTitle }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl text-gray-400">👕</div>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div class="flex-1 min-w-[150px]">
                        <h3 class="font-semibold">
                            <a href="/product/{{ $productHandle }}" class="hover:text-gray-600 transition">
                                {{ $productTitle }}
                            </a>
                        </h3>
                        @if($variantTitle && $variantTitle != 'Default Title')
                            <p class="text-sm text-gray-500">Variant: {{ $variantTitle }}</p>
                        @endif
                        <button class="remove-item text-sm text-red-500 hover:text-red-700 transition" 
                                data-line-id="{{ $node['id'] }}">
                            Remove
                        </button>
                    </div>
                    
                    <!-- Price -->
                    <div class="text-center min-w-[80px]">
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="font-semibold">Rs. {{ number_format((float)$price, 0) }}</p>
                    </div>
                    
                    <!-- Quantity -->
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-gray-500">Qty</p>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                            <button class="qty-decrease px-3 py-1 hover:bg-gray-100 transition text-lg font-bold bg-gray-50" 
                                    data-line-id="{{ $node['id'] }}">
                                −
                            </button>
                            <input type="text" 
                                   class="qty-input w-12 text-center border-0 focus:ring-0 text-sm bg-white"
                                   value="{{ $quantity }}"
                                   readonly
                                   data-line-id="{{ $node['id'] }}">
                            <button class="qty-increase px-3 py-1 hover:bg-gray-100 transition text-lg font-bold bg-gray-50" 
                                    data-line-id="{{ $node['id'] }}">
                                +
                            </button>
                        </div>
                    </div>
                    
                    <!-- Total -->
                    <div class="text-right min-w-[100px]">
                        <p class="text-sm text-gray-500">Total</p>
                        <p class="font-bold text-lg item-total">Rs. {{ number_format((float)$lineTotal, 0) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Cart Summary -->
        <div class="mt-8 flex flex-wrap justify-between items-center gap-4">
            <a href="/products" class="text-gray-600 hover:text-gray-900 transition">
                ← Continue Shopping
            </a>
            <div class="space-y-4">
                <div class="flex justify-between gap-8 text-lg">
                    <span class="font-semibold">Subtotal:</span>
                    <span id="subtotal">Rs. {{ number_format((float)($subtotal ?? 0), 0) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="block w-full bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold text-center hover:bg-gray-700 transition">
                    Proceed to Checkout →
                </a>
            </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // QUANTITY CONTROLS
    // ============================================
    
    document.querySelectorAll('.qty-decrease, .qty-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.cart-item');
            const input = row.querySelector('.qty-input');
            const lineId = input.dataset.lineId || row.dataset.lineId;
            let quantity = parseInt(input.value) || 1;
            
            if (this.classList.contains('qty-decrease')) {
                if (quantity > 1) quantity--;
            } else {
                if (quantity < 10) quantity++;
            }
            
            input.value = quantity;
            updateCart(lineId, quantity);
        });
    });
    
    // ============================================
    // REMOVE ITEM
    // ============================================
    
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const lineId = this.dataset.lineId;
            if (!confirm('Remove this item from cart?')) return;
            
            fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    line_id: lineId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error removing item');
                }
            })
            .catch(() => {
                alert('Error removing item');
            });
        });
    });
    
    // ============================================
    // UPDATE CART
    // ============================================
    
    function updateCart(lineId, quantity) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                line_id: lineId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error updating cart');
            }
        })
        .catch(() => {
            alert('Error updating cart');
        });
    }
    
});
</script>
@endpush
@endsection