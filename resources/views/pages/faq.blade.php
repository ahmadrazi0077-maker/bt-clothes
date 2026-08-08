@extends('layouts.app')

@section('title', 'FAQ')
@section('description', 'Frequently asked questions.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-6xl font-light">Frequently Asked <span class="font-bold">Questions</span></h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto mt-4">Find answers to the most common questions.</p>
    </div>
    
    <div class="max-w-3xl mx-auto space-y-4">
        @php
            $faqs = [
                ['q' => 'What are your shipping options?', 'a' => 'We offer standard shipping (3-5 business days) and express shipping (1-2 business days). Free shipping on orders over $100.'],
                ['q' => 'How do I return an item?', 'a' => 'We offer 30-day returns for all unworn, unwashed items with tags attached. Simply contact our support team to initiate a return.'],
                ['q' => 'Do you ship internationally?', 'a' => 'Yes, we ship worldwide. International shipping rates vary by destination and will be calculated at checkout.'],
                ['q' => 'What sizes do you offer?', 'a' => 'We offer sizes XS through XL for most styles. Please refer to our Size Guide for detailed measurements.'],
                ['q' => 'Are your products sustainable?', 'a' => 'Yes! We are committed to sustainability. Our materials include organic cotton, recycled fabrics, and ethically sourced fibers.'],
                ['q' => 'How can I track my order?', 'a' => 'Once your order is shipped, you will receive a confirmation email with a tracking number to monitor your delivery.'],
            ]
        @endphp
        
        @foreach ($faqs as $index => $faq)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <button class="faq-toggle w-full px-6 py-4 text-left font-semibold flex justify-between items-center hover:bg-gray-50 transition" data-target="faq-{{ $index }}">
                <span>{{ $faq['q'] }}</span>
                <span class="text-xl transition-transform duration-300">▼</span>
            </button>
            <div id="faq-{{ $index }}" class="px-6 pb-4 text-gray-600 hidden">
                {{ $faq['a'] }}
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.faq-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                const icon = this.querySelector('span:last-child');
                
                if (target.classList.contains('hidden')) {
                    target.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    target.classList.add('hidden');
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });
    });
</script>
@endpush
@endsection