@extends('layouts.app')

@section('title', 'Privacy Policy')
@section('description', 'Our privacy policy.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-light text-center mb-8">Privacy <span class="font-bold">Policy</span></h1>
        
        <div class="prose prose-lg max-w-none">
            <p class="text-gray-600">Last updated: January 2026</p>
            
            <h2>Information We Collect</h2>
            <p>We collect information you provide directly to us, such as when you create an account, place an order, or contact us for support. This includes:</p>
            <ul>
                <li>Name and contact information</li>
                <li>Shipping and billing addresses</li>
                <li>Payment information (processed securely)</li>
                <li>Order history and preferences</li>
            </ul>
            
            <h2>How We Use Your Information</h2>
            <ul>
                <li>Process and fulfill your orders</li>
                <li>Communicate with you about your orders</li>
                <li>Send promotional offers and newsletters (with your consent)</li>
                <li>Improve our products and services</li>
            </ul>
            
            <h2>Data Security</h2>
            <p>We implement industry-standard security measures to protect your personal information. All payment transactions are encrypted using SSL technology.</p>
            
            <h2>Third-Party Services</h2>
            <p>We may use third-party services for payment processing, shipping, and marketing. These services have their own privacy policies and are GDPR compliant.</p>
            
            <h2>Your Rights</h2>
            <ul>
                <li>Access your personal data</li>
                <li>Correct inaccurate data</li>
                <li>Request deletion of your data</li>
                <li>Opt-out of marketing communications</li>
            </ul>
            
            <h2>Cookies</h2>
            <p>We use cookies to enhance your shopping experience, analyze site traffic, and personalize content. You can manage cookie preferences in your browser settings.</p>
            
            <div class="bg-gray-50 rounded-xl p-6 mt-8">
                <h3 class="font-semibold">Contact Us</h3>
                <p class="text-sm text-gray-600">If you have any questions about our privacy policy, please contact us.</p>
                <a href="/pages/contact" class="inline-block mt-3 bg-gray-900 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection