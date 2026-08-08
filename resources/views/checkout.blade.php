@extends('layouts.app')

@section('title', 'Checkout')
@section('description', 'Complete your order securely.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <h1 class="text-3xl md:text-4xl font-light mb-8">Secure <span class="font-bold">Checkout</span></h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form action="/checkout/complete" method="POST">
                @csrf
                
                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="font-semibold text-lg mb-4">Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Email</label>
                            <input type="email" value="john@example.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Phone</label>
                            <input type="tel" placeholder="+1 234 567 890" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900">
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Address -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="font-semibold text-lg mb-4">Shipping Address</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">First Name</label>
                            <input type="text" value="John" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Last Name</label>
                            <input type="text" value="Doe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold mb-1">Address</label>
                            <input type="text" placeholder="123 Main Street" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">City</label>
                            <input type="text" placeholder="New York" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">ZIP Code</label>
                            <input type="text" placeholder="10001" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Country</label>
                            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900">
                                <option>United States</option>
                                <option>Canada</option>
                                <option>United Kingdom</option>
                                <option>Australia</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">State</label>
                            <input type="text" placeholder="NY" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="font-semibold text-lg mb-4">Payment Method</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-gray-400 transition">
                            <input type="radio" name="payment" value="card" checked>
                            <span>💳 Credit Card</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-gray-400 transition">
                            <input type="radio" name="payment" value="paypal">
                            <span>💸 PayPal</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-gray-400 transition">
                            <input type="radio" name="payment" value="apple">
                            <span>📱 Apple Pay</span>
                        </label>
                    </div>
                    
                    <!-- Card Details -->
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Card Number</label>
                            <input type="text" placeholder="1234 5678 9012 3456" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Expiry Date</label>
                                <input type="text" placeholder="MM/YY" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">CVV</label>
                                <input type="text" placeholder="123" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900">
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-lg font-semibold text-lg hover:bg-gray-700 transition">
                    Place Order →
                </button>
            </form>
        </div>
        
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                <h3 class="font-semibold text-lg mb-4">Order Summary</h3>
                
                <div class="space-y-4">
                    @for ($i = 1; $i <= 3; $i++)
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center text-xs text-gray-400">
                            Product
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-sm">Product {{ $i }}</div>
                            <div class="text-sm text-gray-500">× {{ $i }}</div>
                        </div>
                        <div class="font-bold">${{ 50 * $i * $i }}</div>
                    </div>
                    @endfor
                </div>
                
                <div class="border-t border-gray-200 mt-4 pt-4 space-y-2 text-sm">
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
                
                <div class="flex justify-center gap-3 mt-4 text-2xl text-gray-400">
                    <span>💳</span>
                    <span>💳</span>
                    <span>💸</span>
                    <span>📱</span>
                </div>
                
                <div class="mt-4 text-xs text-gray-500 text-center">
                    🔒 Your payment is secure and encrypted
                </div>
            </div>
        </div>
    </div>
</div>
@endsection