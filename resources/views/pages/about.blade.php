@extends('layouts.app')

@section('title', 'About Us')
@section('description', 'Learn about our story and mission.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <!-- Hero -->
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-6xl font-light">Our <span class="font-bold">Story</span></h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto mt-4">Timeless clothing for the modern minimalist. Crafted with care and consciousness.</p>
    </div>
    
    <!-- Mission -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
        <div>
            <h2 class="text-3xl font-light mb-4">Mission & <span class="font-bold">Vision</span></h2>
            <p class="text-gray-600 leading-relaxed">At Sanctuary Flow, we believe that fashion should be timeless, not disposable. Our mission is to create clothing that transcends seasons and trends, focusing on quality craftsmanship and sustainable materials.</p>
            <p class="text-gray-600 leading-relaxed mt-4">We envision a world where every piece tells a story — of the hands that made it, the materials that compose it, and the person who wears it.</p>
            <a href="/collections/all" class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Explore Our Collection
            </a>
        </div>
        <div class="bg-gray-200 h-80 rounded-xl flex items-center justify-center text-gray-400">
            Our Story Image
        </div>
    </div>
    
    <!-- Values -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="text-4xl mb-4">🌿</div>
            <h3 class="font-semibold text-lg">Sustainability</h3>
            <p class="text-gray-600 text-sm mt-2">We use organic, recycled, and ethically sourced materials.</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="text-4xl mb-4">✋</div>
            <h3 class="font-semibold text-lg">Craftsmanship</h3>
            <p class="text-gray-600 text-sm mt-2">Each piece is meticulously crafted with attention to detail.</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="text-4xl mb-4">❤️</div>
            <h3 class="font-semibold text-lg">Community</h3>
            <p class="text-gray-600 text-sm mt-2">We believe in building a community of conscious consumers.</p>
        </div>
    </div>
    
    <!-- Team -->
    <div>
        <h2 class="text-3xl font-light text-center mb-12">Meet Our <span class="font-bold">Team</span></h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach ([
                ['name' => 'Sarah Chen', 'role' => 'Founder & Designer'],
                ['name' => 'Marcus Rivera', 'role' => 'Creative Director'],
                ['name' => 'Emma Thompson', 'role' => 'Sustainability Lead'],
                ['name' => 'James Kim', 'role' => 'Operations Manager']
            ] as $member)
            <div class="text-center">
                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto flex items-center justify-center text-3xl text-gray-400">
                    👤
                </div>
                <h4 class="font-semibold mt-3">{{ $member['name'] }}</h4>
                <p class="text-sm text-gray-500">{{ $member['role'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection