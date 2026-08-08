@php
    $slides = [
        [
            'title' => 'Ethereal',
            'highlight' => 'Essentials',
            'description' => 'Discover our latest drop — where minimalism meets organic form.',
            'cta_text' => 'Shop Collection',
            'cta_link' => '/collections/all',
            'badge' => 'New Collection',
            'image' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=400&h=400&fit=crop',
            'bg_from' => 'from-rose-100',
            'bg_to' => 'to-amber-50',
        ],
        [
            'title' => 'Summer',
            'highlight' => 'Sale',
            'description' => 'Up to 50% off on seasonal styles. Limited time only.',
            'cta_text' => 'Explore Sale',
            'cta_link' => '/collections/sale',
            'badge' => 'Summer Sale',
            'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=400&h=400&fit=crop',
            'bg_from' => 'from-blue-100',
            'bg_to' => 'to-cyan-50',
        ],
        [
            'title' => 'Sustainable',
            'highlight' => 'Fashion',
            'description' => 'Made with love for the planet. Organic, recycled, ethical.',
            'cta_text' => 'Learn More',
            'cta_link' => '/collections/sustainable',
            'badge' => 'Eco-Friendly',
            'image' => 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?w=400&h=400&fit=crop',
            'bg_from' => 'from-emerald-100',
            'bg_to' => 'to-green-50',
        ],
    ];
@endphp

<section class="hero-section relative overflow-hidden bg-white">
    <div class="relative">
        <div id="heroSlider" class="relative overflow-hidden">
            <!-- Slides Wrapper -->
            <div id="sliderWrapper" class="flex transition-transform duration-700 ease-in-out">
                @foreach($slides as $index => $slide)
                <div class="min-w-full flex-shrink-0">
                    <div class="relative min-h-[480px] sm:min-h-[500px] md:min-h-[600px] flex items-center bg-gradient-to-br {{ $slide['bg_from'] }} {{ $slide['bg_to'] }}">
                        <div class="container mx-auto px-3 sm:px-4 py-8 sm:py-12 md:py-16">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8 md:gap-12 items-center">
                                <!-- Text Content - Mobile Optimized -->
                                <div class="text-center lg:text-left order-2 lg:order-1">
                                    <!-- Badge - Mobile Friendly -->
                                    <span class="inline-block bg-white/80 backdrop-blur-sm text-gray-800 text-[10px] sm:text-xs md:text-sm font-semibold px-3 sm:px-4 py-1 sm:py-1.5 rounded-full mb-2 sm:mb-4 shadow-sm">
                                        {{ $slide['badge'] }}
                                    </span>
                                    
                                    <!-- Title - Responsive Font -->
                                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-7xl font-light leading-tight mb-1 sm:mb-2 md:mb-4">
                                        <span class="block">{{ $slide['title'] }}</span>
                                        <span class="font-bold block">{{ $slide['highlight'] }}</span>
                                    </h1>
                                    
                                    <!-- Description - Mobile optimized -->
                                    <p class="text-gray-600 text-sm sm:text-base md:text-lg max-w-xs sm:max-w-md mx-auto lg:mx-0 mb-3 sm:mb-4 md:mb-6 leading-relaxed">
                                        {{ $slide['description'] }}
                                    </p>
                                    
                                    <!-- CTA Button - Mobile optimized -->
                                    <a href="{{ $slide['cta_link'] }}" 
                                       class="inline-flex items-center gap-1 sm:gap-2 bg-gray-900 text-white px-5 sm:px-6 md:px-8 py-2.5 sm:py-3 md:py-4 rounded-full font-semibold text-xs sm:text-sm hover:bg-gray-700 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl">
                                        {{ $slide['cta_text'] }}
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </a>
                                    
                                    <!-- Trust Badges - Mobile optimized -->
                                    <div class="flex flex-wrap justify-center lg:justify-start gap-2 sm:gap-3 md:gap-4 mt-3 sm:mt-4 md:mt-6">
                                        <div class="flex items-center gap-1 text-[10px] sm:text-xs text-gray-500">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Free Shipping
                                        </div>
                                        <div class="flex items-center gap-1 text-[10px] sm:text-xs text-gray-500">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            30-Day Returns
                                        </div>
                                        <div class="flex items-center gap-1 text-[10px] sm:text-xs text-gray-500">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Secure Checkout
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Image - Mobile optimized -->
                                <div class="order-1 lg:order-2 flex justify-center">
                                    <div class="relative w-full max-w-[200px] sm:max-w-[280px] md:max-w-sm lg:max-w-md">
                                        <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                                            <img src="{{ $slide['image'] }}" 
                                                 alt="{{ $slide['title'] }} {{ $slide['highlight'] }}"
                                                 class="w-full h-auto object-cover aspect-square"
                                                 loading="lazy"
                                                 width="400"
                                                 height="400">
                                            <!-- Decorative Badge - Mobile optimized -->
                                            <div class="absolute bottom-2 left-2 sm:bottom-4 sm:left-4 bg-white/90 backdrop-blur-sm rounded-lg px-2 py-1 sm:px-3 sm:py-1.5 shadow-lg">
                                                <div class="flex items-center gap-1 sm:gap-2">
                                                    <span class="text-yellow-400 text-[10px] sm:text-xs">★★★★★</span>
                                                    <span class="text-[8px] sm:text-xs text-gray-600">(4.9)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Slider Controls - Mobile optimized -->
            <button id="sliderPrev" class="absolute left-1 sm:left-4 md:left-6 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-full shadow-lg flex items-center justify-center text-sm sm:text-xl transition-all duration-300 hover:scale-110 z-10">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button id="sliderNext" class="absolute right-1 sm:right-4 md:right-6 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 rounded-full shadow-lg flex items-center justify-center text-sm sm:text-xl transition-all duration-300 hover:scale-110 z-10">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <!-- Dots - Mobile optimized -->
            <div class="absolute bottom-2 sm:bottom-4 md:bottom-8 left-1/2 -translate-x-1/2 flex gap-1.5 sm:gap-2 md:gap-3 z-10">
                @foreach($slides as $index => $slide)
                <button class="slider-dot rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-gray-900 w-4 sm:w-6 md:w-8 h-1.5 sm:h-2 md:h-2.5' : 'bg-gray-300 w-1.5 sm:w-2 md:w-2.5 h-1.5 sm:h-2 md:h-2.5 hover:bg-gray-500' }}" 
                        data-index="{{ $index }}"
                        aria-label="Go to slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        </div>
    </div>
</section>

<style>
    .hero-section {
        background: #faf8f5;
    }
    
    .hero-section .transition-transform {
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Mobile Optimizations */
    @media (max-width: 480px) {
        .hero-section .min-h-\[480px\] {
            min-height: 400px;
        }
        
        .hero-section h1 {
            font-size: 1.8rem !important;
            line-height: 1.2;
        }
        
        .hero-section .text-sm {
            font-size: 0.8rem !important;
        }
        
        .hero-section .text-[10px] {
            font-size: 0.6rem !important;
        }
        
        .hero-section .max-w-\[200px\] {
            max-width: 150px;
        }
        
        .hero-section .gap-1 {
            gap: 0.2rem;
        }
        
        .hero-section .px-3 {
            padding-left: 0.6rem;
            padding-right: 0.6rem;
        }
        
        .hero-section .py-2\.5 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
    }
    
    @media (max-width: 360px) {
        .hero-section h1 {
            font-size: 1.4rem !important;
        }
        
        .hero-section .max-w-\[200px\] {
            max-width: 120px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sliderWrapper = document.getElementById('sliderWrapper');
        const slides = sliderWrapper.querySelectorAll('.min-w-full');
        const dots = document.querySelectorAll('.slider-dot');
        const prevBtn = document.getElementById('sliderPrev');
        const nextBtn = document.getElementById('sliderNext');
        let currentIndex = 0;
        let interval;
        const totalSlides = slides.length;
        
        function goToSlide(index) {
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;
            
            currentIndex = index;
            sliderWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
            
            dots.forEach((dot, i) => {
                if (i === currentIndex) {
                    dot.className = 'slider-dot rounded-full transition-all duration-300 bg-gray-900 w-4 sm:w-6 md:w-8 h-1.5 sm:h-2 md:h-2.5';
                } else {
                    dot.className = 'slider-dot rounded-full transition-all duration-300 bg-gray-300 w-1.5 sm:w-2 md:w-2.5 h-1.5 sm:h-2 md:h-2.5 hover:bg-gray-500';
                }
            });
        }
        
        function nextSlide() {
            goToSlide(currentIndex + 1);
        }
        
        function prevSlide() {
            goToSlide(currentIndex - 1);
        }
        
        function startAutoPlay() {
            stopAutoPlay();
            interval = setInterval(nextSlide, 5000);
        }
        
        function stopAutoPlay() {
            if (interval) {
                clearInterval(interval);
                interval = null;
            }
        }
        
        prevBtn.addEventListener('click', function() {
            prevSlide();
            startAutoPlay();
        });
        
        nextBtn.addEventListener('click', function() {
            nextSlide();
            startAutoPlay();
        });
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                goToSlide(index);
                startAutoPlay();
            });
        });
        
        // Touch Support
        let touchStartX = 0;
        let touchEndX = 0;
        const sliderContainer = document.getElementById('heroSlider');
        
        sliderContainer.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        sliderContainer.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            const difference = touchStartX - touchEndX;
            if (Math.abs(difference) > 30) {
                if (difference > 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
                startAutoPlay();
            }
        }, { passive: true });
        
        startAutoPlay();
        
        sliderContainer.addEventListener('mouseenter', stopAutoPlay);
        sliderContainer.addEventListener('mouseleave', startAutoPlay);
    });
</script>