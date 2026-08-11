@extends('layouts.app')

@section('title', 'Shopping Cart')
@section('description', 'Review your items and proceed to checkout.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl md:text-4xl font-light">Shopping <span class="font-bold">Cart</span></h1>
        <span class="text-sm text-gray-500" id="cartItemCount">
            {{ $itemCount ?? 0 }} items
        </span>
    </div>
    
    @if(isset($cart) && $cart && ($itemCount ?? 0) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <!-- Desktop Header -->
                    <div class="hidden md:grid grid-cols-12 gap-4 bg-gray-50 px-6 py-3 text-sm font-semibold text-gray-600 border-b border-gray-100">
                        <div class="col-span-6">Product</div>
                        <div class="col-span-2 text-center">Price</div>
                        <div class="col-span-2 text-center">Quantity</div>
                        <div class="col-span-2 text-right">Total</div>
                    </div>
                    
                    <!-- Cart Items Loop -->
                    <div id="cartItemsContainer">
                        @foreach($cartItems as $item)
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
                                $variantTitle = $merchandise['title'] ?? 'Default';
                                $productTitle = $product['title'] ?? 'Product';
                                $productHandle = $product['handle'] ?? '#';
                            @endphp
                            
                            <div class="cart-item grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-4 md:px-6 py-4 border-b border-gray-100 last:border-0" 
                                 data-line-id="{{ $node['id'] }}"
                                 data-price="{{ $price }}">
                                
                                <!-- Product Info -->
                                <div class="md:col-span-6 flex items-center gap-4">
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">
                                        @if($image)
                                            <img src="{{ $image }}" alt="{{ $productTitle }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl text-gray-400">👕</div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">
                                            <a href="/product/{{ $productHandle }}" class="hover:text-gray-600 transition">
                                                {{ $productTitle }}
                                            </a>
                                        </h3>
                                        @if($variantTitle != 'Default Title' && $variantTitle != 'Default')
                                            <p class="text-sm text-gray-500">Variant: {{ $variantTitle }}</p>
                                        @endif
                                        <button class="remove-item text-sm text-red-500 hover:text-red-700 transition mt-1" 
                                                data-line-id="{{ $node['id'] }}">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Price -->
                                <div class="md:col-span-2 text-center">
                                    <span class="md:hidden font-semibold text-sm">Price: </span>
                                    Rs. {{ number_format((float)$price, 0) }}
                                </div>
                                
                                <!-- ✅ Quantity - Fixed -->
                                <div class="md:col-span-2 flex justify-center">
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                        <button type="button" 
                                                class="qty-btn px-3 py-1 hover:bg-gray-100 transition text-lg font-bold cursor-pointer bg-gray-50"
                                                onclick="updateCartQty(this, -1)">
                                            −
                                        </button>
                                        <input type="text" 
                                               class="qty-input w-12 text-center border-0 focus:ring-0 text-sm bg-white"
                                               value="{{ $quantity }}"
                                               readonly
                                               data-line-id="{{ $node['id'] }}">
                                        <button type="button" 
                                                class="qty-btn px-3 py-1 hover:bg-gray-100 transition text-lg font-bold cursor-pointer bg-gray-50"
                                                onclick="updateCartQty(this, 1)">
                                            +
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Total -->
                                <div class="md:col-span-2 text-right font-bold item-total">
                                    Rs. {{ number_format((float)$lineTotal, 0) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Continue Shopping & Clear Cart -->
                <div class="flex flex-wrap justify-between items-center mt-6 gap-4">
                    <a href="/products" class="text-gray-600 hover:text-gray-900 transition">
                        ← Continue Shopping
                    </a>
                    <button id="clearCart" class="text-red-500 hover:text-red-700 transition">
                        🗑️ Clear Cart
                    </button>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                    <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                    
                    <div class="space-y-3 text-sm" id="cartSummary">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span id="subtotal">Rs. {{ number_format((float)($subtotal ?? 0), 0) }}</span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span id="cartTotal">
                                    Rs. {{ number_format((float)($subtotal ?? 0), 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('checkout') }}" class="block w-full mt-6 bg-gray-900 text-white py-3 rounded-lg font-semibold text-center hover:bg-gray-700 transition">
                        Proceed to Checkout →
                    </a>
                    
                    <div class="flex justify-center gap-3 mt-4 text-2xl text-gray-400">
                        <span>💳</span>
                        <span>💳</span>
                        <span>💸</span>
                        <span>📱</span>
                    </div>
                    
                    <div class="mt-4 text-xs text-gray-500 text-center">
                        🔒 Shipping, tax and discounts calculated at checkout
                    </div>
                </div>
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
// ============================================
// QUANTITY - FINAL FIX
// ============================================

function updateCartQty(btn, change) {
    const row = btn.closest('.cart-item');
    const input = row.querySelector('.qty-input');
    const lineId = input.dataset.lineId || row.dataset.lineId;
    
    let quantity = parseInt(input.value) || 1;
    quantity = quantity + change;
    
    if (quantity < 1) quantity = 1;
    if (quantity > 10) quantity = 10;
    
    input.value = quantity;
    
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
            updateCartSummary();
        } else {
            alert(data.message || 'Error updating cart');
            input.value = quantity - change;
        }
    })
    .catch(() => {
        alert('Error updating cart');
        input.value = quantity - change;
    });
}

// ============================================
// UPDATE CART SUMMARY
// ============================================

function updateCartSummary() {
    let subtotal = 0;
    let itemCount = 0;
    
    document.querySelectorAll('.cart-item').forEach(row => {
        const price = parseFloat(row.dataset.price) || 0;
        const qty = parseInt(row.querySelector('.qty-input').value) || 1;
        const total = price * qty;
        
        subtotal += total;
        itemCount += qty;
        
        const totalEl = row.querySelector('.item-total');
        if (totalEl) {
            totalEl.textContent = 'Rs. ' + total.toFixed(0);
        }
    });
    
    const subtotalEl = document.getElementById('subtotal');
    if (subtotalEl) {
        subtotalEl.textContent = 'Rs. ' + subtotal.toFixed(0);
    }
    
    const totalEl = document.getElementById('cartTotal');
    if (totalEl) {
        totalEl.textContent = 'Rs. ' + subtotal.toFixed(0);
    }
    
    const itemCountEl = document.getElementById('cartItemCount');
    if (itemCountEl) {
        itemCountEl.textContent = itemCount + ' items';
    }
    
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = itemCount;
    }
}

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
                const row = document.querySelector(`.cart-item[data-line-id="${lineId}"]`);
                if (row) row.remove();
                updateCartSummary();
                if (data.itemCount === 0) {
                    location.reload();
                }
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
// CLEAR CART
// ============================================

document.getElementById('clearCart').addEventListener('click', function() {
    if (!confirm('Clear all items from cart?')) return;
    
    fetch('/cart/clear', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(() => {
            alert('Error clearing cart');
        });
});

// ============================================
// INIT
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    updateCartSummary();
});
</script>
@endpush
@endsection