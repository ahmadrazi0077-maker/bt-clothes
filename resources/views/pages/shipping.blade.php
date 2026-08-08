@extends('layouts.app')

@section('title', 'Shipping Information')
@section('description', 'Learn about our shipping policies.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-light text-center mb-8">Shipping <span class="font-bold">Information</span></h1>
        
        <div class="prose prose-lg max-w-none">
            <h2>Shipping Options</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold">Standard Shipping</h3>
                    <p class="text-sm text-gray-600">3-5 business days</p>
                    <p class="text-sm font-bold">$5.99</p>
                    <p class="text-xs text-gray-500">Free on orders over $100</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold">Express Shipping</h3>
                    <p class="text-sm text-gray-600">1-2 business days</p>
                    <p class="text-sm font-bold">$15.99</p>
                </div>
            </div>
            
            <h2>International Shipping</h2>
            <p>We ship worldwide! International shipping rates are calculated at checkout based on your location and order weight. Delivery times vary by destination.</p>
            
            <h2>Order Tracking</h2>
            <p>Once your order has been shipped, you will receive a confirmation email with a tracking number. You can track your order directly on our website or through the carrier's portal.</p>
            
            <h2>Shipping Policy</h2>
            <ul>
                <li>Orders are processed within 1-2 business days.</li>
                <li>We ship Monday through Friday, excluding holidays.</li>
                <li>Shipping delays may occur during peak seasons or due to weather conditions.</li>
                <li>We are not responsible for lost or stolen packages after delivery confirmation.</li>
            </ul>
        </div>
    </div>
</div>
@endsection