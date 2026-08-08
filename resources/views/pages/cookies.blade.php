@extends('layouts.app')

@section('title', 'Cookie Policy')
@section('description', 'Learn about how we use cookies.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-light text-center mb-8">Cookie <span class="font-bold">Policy</span></h1>
        
        <div class="prose prose-lg max-w-none">
            <p class="text-gray-600">Last updated: January 2026</p>
            
            <h2>What Are Cookies?</h2>
            <p>Cookies are small text files that are stored on your browser when you visit a website. They help us remember your preferences and improve your browsing experience.</p>
            
            <h2>How We Use Cookies</h2>
            <ul>
                <li><strong>Essential Cookies:</strong> Required for the website to function properly (e.g., shopping cart, login)</li>
                <li><strong>Analytics Cookies:</strong> Help us understand how visitors interact with our site</li>
                <li><strong>Preference Cookies:</strong> Remember your settings and preferences</li>
                <li><strong>Marketing Cookies:</strong> Personalize ads and measure campaign effectiveness</li>
            </ul>
            
            <h2>Types of Cookies We Use</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Purpose</th>
                            <th class="px-4 py-2 text-left">Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td class="px-4 py-2 border-t">session_id</td><td class="px-4 py-2 border-t">Maintain user session</td><td class="px-4 py-2 border-t">Session</td></tr>
                        <tr><td class="px-4 py-2 border-t">cart_items</td><td class="px-4 py-2 border-t">Store cart contents</td><td class="px-4 py-2 border-t">30 days</td></tr>
                        <tr><td class="px-4 py-2 border-t">_ga</td><td class="px-4 py-2 border-t">Google Analytics tracking</td><td class="px-4 py-2 border-t">2 years</td></tr>
                        <tr><td class="px-4 py-2 border-t">preferences</td><td class="px-4 py-2 border-t">User preferences</td><td class="px-4 py-2 border-t">1 year</td></tr>
                    </tbody>
                </table>
            </div>
            
            <h2>Managing Cookies</h2>
            <p>You can manage or disable cookies in your browser settings. However, disabling cookies may affect your experience on our website.</p>
            
            <h2>Third-Party Cookies</h2>
            <p>We use third-party services (e.g., Google Analytics, payment processors) that may set their own cookies. Please refer to their respective privacy policies.</p>
            
            <div class="bg-gray-50 rounded-xl p-6 mt-8">
                <h3 class="font-semibold">Questions?</h3>
                <p class="text-sm text-gray-600">If you have questions about our cookie policy, please contact us.</p>
                <a href="/pages/contact" class="inline-block mt-3 bg-gray-900 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection