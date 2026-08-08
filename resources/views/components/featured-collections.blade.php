<section class="py-16 md:py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-light">Featured <span class="font-bold">Collections</span></h2>
            <p class="text-gray-600 mt-3">Handpicked for their texture, tone, and timelessness.</p>
            <div class="flex justify-center gap-3 mt-6 flex-wrap">
                <button class="tab-btn px-6 py-2 border-2 border-gray-200 rounded-full text-sm font-semibold hover:bg-gray-900 hover:text-white hover:border-gray-900 transition active" data-tab="all">All</button>
                <button class="tab-btn px-6 py-2 border-2 border-gray-200 rounded-full text-sm font-semibold hover:bg-gray-900 hover:text-white hover:border-gray-900 transition" data-tab="tops">Tops</button>
                <button class="tab-btn px-6 py-2 border-2 border-gray-200 rounded-full text-sm font-semibold hover:bg-gray-900 hover:text-white hover:border-gray-900 transition" data-tab="bottoms">Bottoms</button>
                <button class="tab-btn px-6 py-2 border-2 border-gray-200 rounded-full text-sm font-semibold hover:bg-gray-900 hover:text-white hover:border-gray-900 transition" data-tab="outerwear">Outerwear</button>
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @for ($i = 1; $i <= 8; $i++)
                <div class="product-item bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                    <div class="relative overflow-hidden bg-gray-100">
                        <div class="aspect-[4/5] flex items-center justify-center text-gray-400">
                            Product {{ $i }}
                        </div>
                        <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition">
                            <button class="wishlist-btn w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-lg hover:bg-gray-900 hover:text-white transition" data-product-id="product-{{ $i }}">
                                🤍
                            </button>
                            <button class="quick-view-btn w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-lg hover:bg-gray-900 hover:text-white transition" data-product-id="product-{{ $i }}">
                                👁️
                            </button>
                        </div>
                        @if ($i == 2)
                            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs px-3 py-1 rounded-full font-bold uppercase">Sale</span>
                        @endif
                        @if ($i == 4)
                            <span class="absolute top-3 left-3 bg-green-500 text-white text-xs px-3 py-1 rounded-full font-bold uppercase">New</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold">Product {{ $i }}</h3>
                        <div class="text-yellow-400 text-sm mt-1">⭐⭐⭐⭐½ <span class="text-gray-500 text-xs">(4.5)</span></div>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-lg font-bold">${{ 50 * $i }}</span>
                            @if ($i == 2)
                                <span class="text-gray-400 line-through text-sm">$120</span>
                            @endif
                        </div>
                        <button class="add-to-cart w-full mt-3 py-2 border-2 border-gray-900 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition flex items-center justify-center gap-2" data-product-id="product-{{ $i }}">
                            🛍️ Add to Cart
                        </button>
                    </div>
                </div>
            @endfor
        </div>
        
        <div class="text-center mt-12">
            <a href="/collections/all" class="inline-block px-8 py-3 border-2 border-gray-900 rounded font-semibold uppercase text-sm tracking-wider hover:bg-gray-900 hover:text-white transition">
                View All Products
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab filtering
        const tabs = document.querySelectorAll('.tab-btn');
        const items = document.querySelectorAll('.product-item');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                const tabName = this.dataset.tab;
                items.forEach((item, i) => {
                    item.style.display = (tabName === 'all' || i % 2 === 0) ? 'block' : 'none';
                });
            });
        });
        
        // Add to cart
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                showToast('Product added to cart! 🛒');
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = parseInt(cartCount.textContent) + 1;
                }
            });
        });
        
        // Wishlist
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const isActive = this.classList.toggle('active');
                this.innerHTML = isActive ? '❤️' : '🤍';
                showToast(isActive ? 'Added to wishlist ❤️' : 'Removed from wishlist ❌');
            });
        });
        
        // Quick view
        document.querySelectorAll('.quick-view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const modal = document.querySelector('.quick-view-modal');
                if (modal) {
                    modal.classList.remove('opacity-0', 'invisible');
                    modal.classList.add('opacity-100', 'visible');
                    document.body.style.overflow = 'hidden';
                }
            });
        });
    });
</script>
@endpush