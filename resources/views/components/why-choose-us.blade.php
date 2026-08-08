@props(['features' => []])

@php
    // Default features if no data from Shopify
    $defaultFeatures = [
        [
            'icon' => '✨',
            'title' => 'Premium Quality',
            'description' => 'Every piece is crafted with the finest materials and attention to detail',
            'color' => 'from-purple-100 to-pink-100'
        ],
        [
            'icon' => '🌿',
            'title' => 'Sustainable Fashion',
            'description' => 'Eco-friendly materials and ethical production practices',
            'color' => 'from-green-100 to-emerald-100'
        ],
        [
            'icon' => '🚚',
            'title' => 'Free Shipping',
            'description' => 'Free shipping on orders over $100, delivered to your door',
            'color' => 'from-blue-100 to-cyan-100'
        ],
        [
            'icon' => '💝',
            'title' => 'Customer Care',
            'description' => '24/7 customer support and hassle-free returns',
            'color' => 'from-rose-100 to-pink-100'
        ],
        [
            'icon' => '🔄',
            'title' => 'Easy Returns',
            'description' => '30-day hassle-free returns on all products',
            'color' => 'from-orange-100 to-amber-100'
        ],
        [
            'icon' => '🔒',
            'title' => 'Secure Checkout',
            'description' => 'Your payment and personal information are always safe',
            'color' => 'from-indigo-100 to-blue-100'
        ],
    ];
    
    $features = !empty($features) ? $features : $defaultFeatures;
@endphp

<section class="why-choose-us-section py-16 md:py-20 bg-white">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-12 md:mb-16">
            <span class="text-sm text-gray-500 uppercase tracking-wider font-semibold">Why Choose Us</span>
            <h2 class="text-3xl md:text-4xl font-light mt-2">
                Why <span class="font-bold">BT Clothes</span>
            </h2>
            <p class="text-gray-600 mt-3 max-w-2xl mx-auto">
                We're committed to providing you with the best shopping experience
            </p>
        </div>
        
        <!-- Features Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($features as $index => $feature)
                <div class="feature-card group bg-gradient-to-br {{ $feature['color'] ?? 'from-gray-100 to-gray-200' }} rounded-2xl p-6 md:p-8 text-center hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full -mr-10 -mt-10"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/20 rounded-full -ml-8 -mb-8"></div>
                    
                    <!-- Icon -->
                    <div class="feature-icon w-20 h-20 bg-white/80 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-5 text-4xl shadow-lg group-hover:scale-110 transition-transform duration-300 relative z-10">
                        {{ $feature['icon'] ?? '🌟' }}
                    </div>
                    
                    <!-- Title -->
                    <h3 class="font-semibold text-xl mb-2 relative z-10">
                        {{ $feature['title'] ?? 'Feature' }}
                    </h3>
                    
                    <!-- Description -->
                    <p class="text-gray-600 text-sm leading-relaxed max-w-xs mx-auto relative z-10">
                        {{ $feature['description'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' }}
                    </p>
                    
                    <!-- Learn More Link -->
                    @if(isset($feature['link']))
                        <a href="{{ $feature['link'] }}" class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-gray-800 hover:text-gray-900 transition relative z-10 group">
                            Learn More
                            <span class="transform group-hover:translate-x-1 transition">→</span>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- Stats Banner -->
        <div class="mt-12 md:mt-16 bg-gray-900 rounded-2xl p-8 md:p-12 text-white relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-16 -mb-16"></div>
            
            <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 text-center">
                <div class="stat-item">
                    <div class="text-3xl md:text-4xl font-bold count-up" data-target="10000">0</div>
                    <div class="text-sm text-gray-400 mt-1">Happy Customers</div>
                    <div class="w-12 h-0.5 bg-white/20 mx-auto mt-2"></div>
                </div>
                <div class="stat-item">
                    <div class="text-3xl md:text-4xl font-bold count-up" data-target="500">0</div>
                    <div class="text-sm text-gray-400 mt-1">Products</div>
                    <div class="w-12 h-0.5 bg-white/20 mx-auto mt-2"></div>
                </div>
                <div class="stat-item">
                    <div class="text-3xl md:text-4xl font-bold count-up" data-target="98">0</div>
                    <div class="text-sm text-gray-400 mt-1">Satisfaction Rate</div>
                    <div class="w-12 h-0.5 bg-white/20 mx-auto mt-2"></div>
                </div>
                <div class="stat-item">
                    <div class="text-3xl md:text-4xl font-bold count-up" data-target="4.9">0</div>
                    <div class="text-sm text-gray-400 mt-1">Average Rating</div>
                    <div class="w-12 h-0.5 bg-white/20 mx-auto mt-2"></div>
                </div>
            </div>
        </div>
        
        <!-- Trust Badges -->
        <div class="mt-8 flex flex-wrap justify-center gap-6 md:gap-10">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="text-xl">🏆</span>
                Trusted by 10,000+ customers
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="text-xl">⭐</span>
                4.9/5 average rating
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="text-xl">🔒</span>
                100% secure checkout
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="text-xl">🌍</span>
                Worldwide shipping
            </div>
        </div>
    </div>
</section>

<style>
    .feature-card {
        border: 1px solid rgba(0,0,0,0.04);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .feature-card:hover {
        border-color: rgba(0,0,0,0.08);
    }
    
    .feature-icon {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .feature-card:hover .feature-icon {
        transform: scale(1.1) rotate(-5deg);
        background: white;
    }
    
    /* Stats Counter Animation */
    .stat-item {
        transition: all 0.3s ease;
    }
    
    .stat-item:hover {
        transform: scale(1.05);
    }
    
    @media (max-width: 640px) {
        .feature-card {
            padding: 1.5rem;
        }
        .feature-icon {
            width: 3.5rem;
            height: 3.5rem;
            font-size: 1.75rem;
        }
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Counter Animation
    const counters = document.querySelectorAll('.count-up');
    
    const animateCounter = (counter) => {
        const target = parseFloat(counter.dataset.target);
        const isDecimal = target % 1 !== 0;
        const duration = 2000;
        const stepTime = 16;
        const steps = duration / stepTime;
        let current = 0;
        
        const increment = target / steps;
        
        const updateCounter = () => {
            current += increment;
            if (current >= target) {
                current = target;
                counter.textContent = isDecimal ? target.toFixed(1) : Math.floor(target);
                return;
            }
            counter.textContent = isDecimal ? current.toFixed(1) : Math.floor(current);
            requestAnimationFrame(updateCounter);
        };
        
        // Start animation when visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(counter);
    };
    
    counters.forEach(animateCounter);
});
</script>
@endpush