@extends('layouts.app')

@section('title', 'Terms of Service')
@section('description', 'Our terms and conditions.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-light text-center mb-8">Terms of <span class="font-bold">Service</span></h1>
        
        <div class="prose prose-lg max-w-none">
            <p class="text-gray-600">Last updated: January 2026</p>
            
            <h2>1. Acceptance of Terms</h2>
            <p>By using Sanctuary Flow's website and services, you agree to be bound by these Terms of Service.</p>
            
            <h2>2. Account Registration</h2>
            <p>You must be at least 18 years old to create an account. You are responsible for maintaining the security of your account credentials.</p>
            
            <h2>3. Products and Pricing</h2>
            <ul>
                <li>All product prices are listed in USD</li>
                <li>We reserve the right to update prices and availability</li>
                <li>Product images are for illustrative purposes only</li>
                <li>We strive to provide accurate product descriptions</li>
            </ul>
            
            <h2>4. Orders and Payment</h2>
            <ul>
                <li>Orders are subject to acceptance and availability</li>
                <li>Payment must be made in full at checkout</li>
                <li>We accept major credit cards and PayPal</li>
                <li>We reserve the right to cancel any order</li>
            </ul>
            
            <h2>5. Shipping and Delivery</h2>
            <ul>
                <li>Shipping times are estimates and not guaranteed</li>
                <li>Risk of loss transfers to you upon delivery</li>
                <li>International orders may be subject to customs duties</li>
            </ul>
            
            <h2>6. Returns and Refunds</h2>
            <p>Please refer to our <a href="/pages/returns">Returns Policy</a> for detailed information about returns and exchanges.</p>
            
            <h2>7. Intellectual Property</h2>
            <p>All content on our website, including product images, designs, and text, is protected by copyright and intellectual property laws.</p>
            
            <h2>8. Limitation of Liability</h2>
            <p>Sanctuary Flow is not liable for any indirect, incidental, or consequential damages arising from the use of our products or services.</p>
            
            <div class="bg-gray-50 rounded-xl p-6 mt-8">
                <h3 class="font-semibold">Questions?</h3>
                <p class="text-sm text-gray-600">If you have any questions about our Terms of Service, please contact us.</p>
                <a href="/pages/contact" class="inline-block mt-3 bg-gray-900 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection