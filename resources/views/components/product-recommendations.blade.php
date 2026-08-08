<section class="py-16 md:py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-light">You May <span class="font-bold">Also Like</span></h2>
            <p class="text-gray-600 mt-3">Complete your look with these handpicked pieces.</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @for ($i = 1; $i <= 4; $i++)
                <div class="product-item bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="relative overflow-hidden bg-gray-100">
                        <div class="aspect-[4/5] flex items-center justify-center text-gray-400">
                            Recommendation {{ $i }}
                        </div>
                        <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition">
                            <button class="wishlist-btn w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-lg hover:bg-gray-900 hover:text-white transition" data-product-id="rec-{{ $i }}" onclick="toggleWishlist('rec-{{ $i }}')">
                                🤍
                            </button>
                            <button class="quick-view-btn w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-lg hover:bg-gray-900 hover:text-white transition" data-product-id="rec-{{ $i }}">
                                👁️
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold">Recommendation {{ $i }}</h3>
                        <div class="text-lg font-bold mt-1">${{ 50 * $i + 20 }}</div>
                        <button class="add-to-cart w-full mt-3 py-2 border-2 border-gray-900 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition flex items-center justify-center gap-2" data-product-id="rec-{{ $i }}">
                            🛍️ Add to Cart
                        </button>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>