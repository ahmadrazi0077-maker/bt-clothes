@extends('layouts.app')

@section('title', 'Returns & Exchanges')
@section('description', 'Learn about our return policy.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-light text-center mb-8">Returns &amp; <span class="font-bold">Exchanges</span></h1>
        
        <div class="prose prose-lg max-w-none">
            <h2>Our Return Policy</h2>
            <p>We want you to love your purchase! If you're not completely satisfied, we offer a <strong>30-day return policy</strong> from the date of delivery.</p>
            
            <h2>Return Conditions</h2>
            <ul>
                <li>Items must be unworn, unwashed, and in original condition</li>
                <li>All tags must be attached</li>
                <li>Original packaging and receipt required</li>
                <li>Sale items are final sale unless defective</li>
            </ul>
            
            <h2>How to Return</h2>
            <ol>
                <li>Contact our support team at <a href="mailto:returns@sanctuaryflow.com">returns@sanctuaryflow.com</a></li>
                <li>Include your order number and reason for return</li>
                <li>We'll provide you with a return shipping label</li>
                <li>Pack your items securely and ship them back</li>
                <li>Once received, we'll process your refund within 5-7 business days</li>
            </ol>
            
            <h2>Exchanges</h2>
            <p>If you need a different size or color, please start a return and place a new order for the correct item. This ensures you get your new item as quickly as possible.</p>
            
            <h2>Refund Method</h2>
            <p>Refunds will be issued to your original payment method within 5-7 business days after we receive your return. You will receive a confirmation email when your refund has been processed.</p>
            
            <div class="bg-gray-50 rounded-xl p-6 mt-8">
                <h3 class="font-semibold">Need Help?</h3>
                <p class="text-sm text-gray-600">Our team is here to assist you with any questions about returns or exchanges.</p>
                <a href="/pages/contact" class="inline-block mt-3 bg-gray-900 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection