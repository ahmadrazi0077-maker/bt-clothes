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
                            @endphp
                            
                            <div class="cart-item grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-4 md:px-6 py-4 border-b border-gray-100 last:border-0" 
                                 data-line-id="{{ $node['id'] }}">
                                
                                <!-- Product Info -->
                                <div class="md:col-span-6 flex items-center gap-4">
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">
                                        @if($image)
                                            <img src="{{ $image }}" alt="{{ $product['title'] ?? 'Product' }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl text-gray-400">
                                                👕
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">
                                            <a href="/product/{{ $product['handle'] ?? '' }}" class="hover:text-gray-600 transition">
                                                {{ $product['title'] ?? 'Product' }}
                                            </a>
                                        </h3>
                                        @if($variantTitle != 'Default Title')
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
                                    ${{ number_format((float)$price, 2) }}
                                </div>
                                
                                <!-- Quantity -->
                                <div class="md:col-span-2 flex justify-center">
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button class="qty-decrease px-3 py-1 hover:bg-gray-100 transition" 
                                                data-line-id="{{ $node['id'] }}">
                                            -
                                        </button>
                                        <input type="number" value="{{ $quantity }}" min="1" max="10" 
                                               class="qty-input w-12 text-center border-0 focus:ring-0 text-sm"
                                               data-line-id="{{ $node['id'] }}">
                                        <button class="qty-increase px-3 py-1 hover:bg-gray-100 transition" 
                                                data-line-id="{{ $node['id'] }}">
                                            +
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Total -->
                                <div class="md:col-span-2 text-right font-bold item-total">
                                    ${{ number_format((float)$lineTotal, 2) }}
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
                            <span id="subtotal">${{ number_format((float)($subtotal ?? 0), 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-green-600">Free</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span id="tax">${{ number_format((float)($tax ?? 0), 2) }}</span>
                        </div>
                        
                        <!-- Discount Section -->
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex gap-2">
                                <input type="text" id="discountCode" placeholder="Promo code" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900">
                                <button id="applyDiscount" class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
                                    Apply
                                </button>
                            </div>
                            <div id="discountMessage" class="text-xs mt-1 hidden"></div>
                        </div>
                        
                        <!-- Total -->
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span id="cartTotal">${{ number_format((float)($total ?? 0), 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Checkout Button -->
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
                        🔒 Secure checkout powered by Shopify
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
document.addEventListener('DOMContentLoaded', function() {
    const cartContainer = document.getElementById('cartItemsContainer');
    const subtotalEl = document.getElementById('subtotal');
    const taxEl = document.getElementById('tax');
    const totalEl = document.getElementById('cartTotal');
    const itemCountEl = document.getElementById('cartItemCount');
    const discountInput = document.getElementById('discountCode');
    const applyDiscountBtn = document.getElementById('applyDiscount');
    const discountMessage = document.getElementById('discountMessage');
    const clearCartBtn = document.getElementById('clearCart');
    
    // ===== Update Cart Summary =====
    function updateCartSummary(cart) {
        if (cart && cart.estimatedCost) {
            const subtotal = cart.estimatedCost.subtotalAmount?.amount || 0;
            const tax = cart.estimatedCost.totalTaxAmount?.amount || 0;
            const total = cart.estimatedCost.totalAmount?.amount || 0;
            const count = cart.totalQuantity || 0;
            
            if (subtotalEl) subtotalEl.textContent = '$' + parseFloat(subtotal).toFixed(2);
            if (taxEl) taxEl.textContent = '$' + parseFloat(tax).toFixed(2);
            if (totalEl) totalEl.textContent = '$' + parseFloat(total).toFixed(2);
            if (itemCountEl) itemCountEl.textContent = count + ' items';
        }
    }
    
    // ===== Update Item Total =====
    function updateItemTotal(row, price, quantity) {
        const total = price * quantity;
        const totalEl = row.querySelector('.item-total');
        if (totalEl) {
            totalEl.textContent = '$' + total.toFixed(2);
        }
    }
    
    // ===== Refresh Cart =====
    function refreshCart() {
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                if (data.count !== undefined) {
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) cartCount.textContent = data.count;
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // ===== Update Cart API Call =====
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
                updateCartSummary(data.cart);
                refreshCart();
                
                // Update item row
                const row = document.querySelector(`.cart-item[data-line-id="${lineId}"]`);
                if (row) {
                    const input = row.querySelector('.qty-input');
                    const price = parseFloat(row.querySelector('.md\\:col-span-2').textContent.replace('$', ''));
                    updateItemTotal(row, price, quantity);
                }
            } else {
                showToast(data.message || 'Error updating cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating cart');
        });
    }
    
    // ===== Remove Item =====
    function removeItem(lineId) {
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
                updateCartSummary(data.cart);
                refreshCart();
                showToast('Item removed from cart');
                
                // Check if cart is empty
                if (data.itemCount === 0) {
                    location.reload();
                }
            } else {
                showToast(data.message || 'Error removing item');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error removing item');
        });
    }
    
    // ===== Apply Discount =====
    applyDiscountBtn?.addEventListener('click', function() {
        const code = discountInput.value.trim();
        if (!code) {
            discountMessage.textContent = 'Please enter a discount code';
            discountMessage.className = 'text-xs mt-1 text-red-500';
            discountMessage.classList.remove('hidden');
            return;
        }
        
        fetch('/cart/discount', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                discount_code: code
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                discountMessage.textContent = '✅ ' + data.message;
                discountMessage.className = 'text-xs mt-1 text-green-500';
                discountMessage.classList.remove('hidden');
                updateCartSummary(data.cart);
            } else {
                discountMessage.textContent = '❌ ' + data.message;
                discountMessage.className = 'text-xs mt-1 text-red-500';
                discountMessage.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            discountMessage.textContent = 'Error applying discount';
            discountMessage.className = 'text-xs mt-1 text-red-500';
            discountMessage.classList.remove('hidden');
        });
    });
    
    // ===== Clear Cart =====
    clearCartBtn?.addEventListener('click', function() {
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
        .catch(error => console.error('Error:', error));
    });
    
    // ===== Quantity Controls =====
    document.querySelectorAll('.qty-decrease, .qty-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('.cart-item');
            const input = row.querySelector('.qty-input');
            const lineId = input.dataset.lineId || row.dataset.lineId;
            let quantity = parseInt(input.value);
            
            if (this.classList.contains('qty-decrease')) {
                if (quantity > 1) quantity--;
            } else {
                if (quantity < 10) quantity++;
            }
            
            input.value = quantity;
            updateCart(lineId, quantity);
        });
    });
    
    // ===== Quantity Input Change =====
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            let quantity = parseInt(this.value) || 1;
            if (quantity < 1) quantity = 1;
            if (quantity > 10) quantity = 10;
            this.value = quantity;
            
            const lineId = this.dataset.lineId || this.closest('.cart-item').dataset.lineId;
            updateCart(lineId, quantity);
        });
    });
    
    // ===== Remove Item Button =====
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const lineId = this.dataset.lineId;
            removeItem(lineId);
        });
    });
    
    // ===== Initial Refresh =====
    refreshCart();
});
</script>
@endpush
@endsection