@extends('layouts.app')

@section('title', 'Size Guide')
@section('description', 'Find your perfect fit.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-6xl font-light">Size <span class="font-bold">Guide</span></h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto mt-4">Find your perfect fit with our comprehensive size chart.</p>
    </div>
    
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-6 py-4 text-left font-semibold border border-gray-200">Size</th>
                            <th class="px-6 py-4 text-left font-semibold border border-gray-200">Bust (in)</th>
                            <th class="px-6 py-4 text-left font-semibold border border-gray-200">Waist (in)</th>
                            <th class="px-6 py-4 text-left font-semibold border border-gray-200">Hip (in)</th>
                            <th class="px-6 py-4 text-left font-semibold border border-gray-200">US Size</th>
                            <th class="px-6 py-4 text-left font-semibold border border-gray-200">EU Size</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sizes = [
                                ['XS', '31-32', '24-25', '34-35', '0-2', '32-34'],
                                ['S', '33-34', '26-27', '36-37', '4-6', '34-36'],
                                ['M', '35-36', '28-29', '38-39', '8-10', '38-40'],
                                ['L', '37-38', '30-31', '40-41', '12-14', '42-44'],
                                ['XL', '39-40', '32-33', '42-43', '16-18', '46-48'],
                            ];
                        @endphp
                        @foreach ($sizes as $size)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 border border-gray-200 font-semibold">{{ $size[0] }}</td>
                            <td class="px-6 py-4 border border-gray-200">{{ $size[1] }}</td>
                            <td class="px-6 py-4 border border-gray-200">{{ $size[2] }}</td>
                            <td class="px-6 py-4 border border-gray-200">{{ $size[3] }}</td>
                            <td class="px-6 py-4 border border-gray-200">{{ $size[4] }}</td>
                            <td class="px-6 py-4 border border-gray-200">{{ $size[5] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <span class="text-3xl block mb-2">📏</span>
                <h4 class="font-semibold">Bust</h4>
                <p class="text-sm text-gray-600">Measure around the fullest part of your chest</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <span class="text-3xl block mb-2">📐</span>
                <h4 class="font-semibold">Waist</h4>
                <p class="text-sm text-gray-600">Measure around your natural waistline</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                <span class="text-3xl block mb-2">📊</span>
                <h4 class="font-semibold">Hip</h4>
                <p class="text-sm text-gray-600">Measure around the fullest part of your hips</p>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <p class="text-sm text-gray-500">Need help finding your size? <a href="/pages/contact" class="text-gray-900 font-semibold hover:underline">Contact our team</a></p>
        </div>
    </div>
</div>
@endsection