@extends('layouts.app')

@section('title', 'Shopping Cart')
@section('description', 'Review your items and proceed to checkout.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <h1 class="text-3xl md:text-4xl font-light mb-8">Shopping <span class="font-bold">Cart</span></h1>
    
    @if (session('cart') && count(session('cart')) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="hidden md:grid grid-cols-12 gap-4 bg-gray-50 px-6 py-3 text-sm font-semibold text-gray-600">
                        <div class="col-span-6">Product</div>
                        <div class="col-span-2 text-center">Price</div>
                        <div class="col-span-2 text-center">Quantity</div>
                        <div class="col-span-2 text-right">Total</div>
                    </div>
                    
                    @for ($i = 1; $i <= 3; $i++)
                    <div class="cart-item grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-4 md:px-6 py-4 border-b border-gray-100 last:border-0">
                        <!-- Product Info -->
                        <div class="md:col-span-6 flex items-center gap-4">
                            <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center text-xs text-gray-400">
                                Product {{ $i }}
                            </div>
                            <div>
                                <h3 class="font-semibold">Product {{ $i }}</h3>
                                <p class="text-sm text-gray-500">Size: M, Color: Black</p>
                                <button class="text-sm text-red-500 hover:text-red-700 transition mt-1">Remove</button>
                            </div>
                        </div>
                        
                        <!-- Price -->
                        <div class="md:col-span-2 text-center">
                            <span class="md:hidden font-semibold text-sm">Price: </span>
                            ${{ 50 * $i }}
                        </div>
                        
                        <!-- Quantity -->
                        <div class="md:col-span-2 flex justify-center">
                            <div class="flex items-center border border-gray-300 rounded-lg">
                                <button class="qty-decrease px-3 py-1 hover:bg-gray-100 transition">-</button>
                                <input type="number" value="{{ $i }}" min="1" max="10" class="w-12 text-center border-0 focus:ring-0 text-sm">
                                <button class="qty-increase px-3 py-1 hover:bg-gray-100 transition">+</button>
                            </div>
                        </div>
                        
                        <!-- Total -->
                        <div class="md:col-span-2 text-right font-bold">
                            ${{ 50 * $i * $i }}
                        </div>
                    </div>
                    @endfor
                </div>
                
                <!-- Continue Shopping -->
                <a href="/collections/all" class="inline-block mt-6 text-gray-600 hover:text-gray-900 transition">
                    ← Continue Shopping
                </a>
            </div>
            
            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                    <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>$450.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-green-600">Free</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span>$45.00</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span>$495.00</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Promo Code -->
                    <div class="mt-4">
                        <div class="flex gap-2">
                            <input type="text" placeholder="Promo code" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-900">
                            <button class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
                                Apply
                            </button>
                        </div>
                    </div>
                    
                    <a href="/checkout" class="block w-full mt-6 bg-gray-900 text-white py-3 rounded-lg font-semibold text-center hover:bg-gray-700 transition">
                        Proceed to Checkout →
                    </a>
                    
                    <div class="flex justify-center gap-3 mt-4 text-2xl text-gray-400">
                        <span>💳</span>
                        <span>💳</span>
                        <span>💸</span>
                        <span>📱</span>
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
            <a href="/collections/all" class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Start Shopping
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity controls for cart
        document.querySelectorAll('.cart-item .qty-controls').forEach(controls => {
            const input = controls.querySelector('input');
            const decrease = controls.querySelector('.qty-decrease');
            const increase = controls.querySelector('.qty-increase');
            
            if (decrease && input) {
                decrease.addEventListener('click', () => {
                    if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
                });
            }
            if (increase && input) {
                increase.addEventListener('click', () => {
                    if (parseInt(input.value) < 10) input.value = parseInt(input.value) + 1;
                });
            }
        });
        
        // Remove item
        document.querySelectorAll('.cart-item .text-red-500').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Remove this item from cart?')) {
                    this.closest('.cart-item').style.display = 'none';
                    showToast('Item removed from cart ❌');
                }
            });
        });
    });
</script>
@endpush
@endsection