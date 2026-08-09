@php
    $routes = [
        'root' => '/',
        'all_products' => '/collections/all',
        'collections' => '/collections',
        'cart' => '/cart',
        'account' => '/account',
        'search' => '/search',
    ];
    
    // Check if user is logged in (Laravel session)
    $isLoggedIn = session()->has('customer_access_token');
@endphp


<header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4 gap-4">
            <!-- Logo -->
            <div class="logo flex-shrink-0">
                <a href="{{ $routes['root'] }}" class="text-2xl font-light tracking-widest">
                    BT <span class="font-bold">Clothes</span>
                </a>
                <span class="text-xs text-gray-400 hidden md:inline-block ml-2">by Bin Tariq</span>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex flex-1 justify-center">
                <ul class="flex items-center gap-6 xl:gap-8">
                    <li class="relative group">
                        <a href="{{ $routes['all_products'] }}" class="text-sm font-semibold uppercase tracking-wider hover:text-gray-600 transition flex items-center gap-1">
                            Shop 
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                        <div class="absolute top-full left-1/2 -translate-x-1/2 bg-white shadow-2xl rounded-xl p-6 w-[700px] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 border-t-2 border-gray-900 z-50">
                            <div class="grid grid-cols-4 gap-6">
                                <div>
                                    <h4 class="text-xs uppercase tracking-wider text-gray-500 mb-3 font-semibold">Categories</h4>
                                    <ul class="space-y-2">
                                        <li><a href="{{ $routes['collections'] }}/new-arrivals" class="text-sm text-gray-600 hover:text-gray-900 transition">New Arrivals</a></li>
                                        <li><a href="{{ $routes['collections'] }}/bestsellers" class="text-sm text-gray-600 hover:text-gray-900 transition">Bestsellers</a></li>
                                        <li><a href="{{ $routes['collections'] }}/sale" class="text-sm text-gray-600 hover:text-gray-900 transition">Sale</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-xs uppercase tracking-wider text-gray-500 mb-3 font-semibold">Clothing</h4>
                                    <ul class="space-y-2">
                                        <li><a href="{{ $routes['collections'] }}/tops" class="text-sm text-gray-600 hover:text-gray-900 transition">Tops</a></li>
                                        <li><a href="{{ $routes['collections'] }}/bottoms" class="text-sm text-gray-600 hover:text-gray-900 transition">Bottoms</a></li>
                                        <li><a href="{{ $routes['collections'] }}/outerwear" class="text-sm text-gray-600 hover:text-gray-900 transition">Outerwear</a></li>
                                        <li><a href="{{ $routes['collections'] }}/dresses" class="text-sm text-gray-600 hover:text-gray-900 transition">Dresses</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-xs uppercase tracking-wider text-gray-500 mb-3 font-semibold">Accessories</h4>
                                    <ul class="space-y-2">
                                        <li><a href="{{ $routes['collections'] }}/bags" class="text-sm text-gray-600 hover:text-gray-900 transition">Bags</a></li>
                                        <li><a href="{{ $routes['collections'] }}/jewelry" class="text-sm text-gray-600 hover:text-gray-900 transition">Jewelry</a></li>
                                        <li><a href="{{ $routes['collections'] }}/scarves" class="text-sm text-gray-600 hover:text-gray-900 transition">Scarves</a></li>
                                    </ul>
                                </div>
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-lg text-center">
                                    <div class="bg-gray-200 h-24 rounded mb-3 flex items-center justify-center text-3xl text-gray-500">
                                        ☀️
                                    </div>
                                    <h4 class="font-semibold text-sm">Summer Collection</h4>
                                    <p class="text-xs text-gray-600">Shop now and get 20% off</p>
                                    <a href="{{ $routes['collections'] }}/summer" class="inline-block mt-2 bg-gray-900 text-white px-4 py-1.5 text-xs rounded hover:bg-gray-700 transition">
                                        Shop Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li><a href="/collections" class="text-sm font-semibold uppercase tracking-wider hover:text-gray-600 transition">Collections</a></li>
                    <li><a href="/categories" class="text-sm font-semibold uppercase tracking-wider hover:text-gray-600 transition">Categories</a></li>
                    <li><a href="/products" class="text-sm font-semibold uppercase tracking-wider hover:text-gray-600 transition">Shop</a></li>
                    <li><a href="/blog" class="text-sm font-semibold uppercase tracking-wider hover:text-gray-600 transition">Journal</a></li>
                    <li><a href="/pages/about" class="text-sm font-semibold uppercase tracking-wider hover:text-gray-600 transition">About</a></li>
                    <li><a href="/pages/contact" class="text-sm font-semibold uppercase tracking-wider hover:text-gray-600 transition">Contact</a></li>
                </ul>
            </nav>
            
            <!-- Header Actions -->
            <div class="flex items-center gap-3 md:gap-4">

                <button id="searchToggle" class="text-xl hover:text-gray-600 transition" aria-label="Search">
                    🔍
                </button>
                <!-- Account Button -->
<a href="{{ $isLoggedIn ? route('account.dashboard') : 'https://bin-tariq-collection.myshopify.com/account/login' }}" 
   class="text-xl hover:text-gray-600 transition relative" aria-label="Account">
    👤
    @if($isLoggedIn)
        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-green-500 rounded-full"></span>
    @endif
</a>
<!-- In header actions -->

                <a href="/cart" class="text-xl hover:text-gray-600 transition relative" id="cartLink" aria-label="Cart">
                    🛒
                    <span class="absolute -top-2 -right-3 bg-gray-900 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center cart-count">
                        0
                    </span>
                </a>
                <button id="mobileToggle" class="lg:hidden text-2xl hover:text-gray-600 transition" aria-label="Menu">
                    ☰
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobileNav" class="lg:hidden hidden bg-white border-t border-gray-200 py-4">
            <ul class="flex flex-col space-y-2">
                <li><a href="{{ $routes['all_products'] }}" class="block px-4 py-2 hover:bg-gray-50 rounded text-sm font-medium">Shop</a></li>
                <li><a href="/collections" class="block px-4 py-2 hover:bg-gray-50 rounded text-sm font-medium">Collections</a></li>
                <li><a href="/categories" class="block px-4 py-2 hover:bg-gray-50 rounded text-sm font-medium">Categories</a></li>
                <li><a href="/products" class="block px-4 py-2 hover:bg-gray-50 rounded text-sm font-medium">All Products</a></li>
                <li><a href="/blog" class="block px-4 py-2 hover:bg-gray-50 rounded text-sm font-medium">Journal</a></li>
                <li><a href="/pages/about" class="block px-4 py-2 hover:bg-gray-50 rounded text-sm font-medium">About</a></li>
                <li><a href="/pages/contact" class="block px-4 py-2 hover:bg-gray-50 rounded text-sm font-medium">Contact</a></li>
            </ul>
        </div>
    </div>
</header>

<!-- Search Overlay -->
<div id="searchOverlay" class="fixed inset-0 bg-black/90 z-[9999] flex items-center justify-center opacity-0 invisible transition-all duration-300">
    <div class="w-full max-w-2xl mx-4">
        <form action="{{ $routes['search'] }}" method="GET" class="flex gap-3">
            <input type="search" name="q" placeholder="Search for products..." class="flex-1 px-6 py-5 text-xl rounded-lg border-0 focus:ring-2 focus:ring-gray-900 bg-white" autofocus>
            <button type="submit" class="bg-gray-900 text-white px-6 py-5 rounded-lg text-xl hover:bg-gray-700 transition">🔍</button>
        </form>
        <button id="searchClose" class="absolute top-8 right-8 text-white text-3xl hover:text-gray-300 transition">✕</button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search toggle
    const searchToggle = document.getElementById('searchToggle');
    const searchOverlay = document.getElementById('searchOverlay');
    const searchClose = document.getElementById('searchClose');
    
    if (searchToggle && searchOverlay) {
        searchToggle.addEventListener('click', () => {
            searchOverlay.classList.remove('opacity-0', 'invisible');
            searchOverlay.classList.add('opacity-100', 'visible');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                searchOverlay.querySelector('input')?.focus();
            }, 100);
        });
    }
    
    if (searchClose && searchOverlay) {
        searchClose.addEventListener('click', () => {
            searchOverlay.classList.add('opacity-0', 'invisible');
            searchOverlay.classList.remove('opacity-100', 'visible');
            document.body.style.overflow = 'auto';
        });
    }
    
    if (searchOverlay) {
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('opacity-0', 'invisible');
                this.classList.remove('opacity-100', 'visible');
                document.body.style.overflow = 'auto';
            }
        });
    }
    
    // Mobile menu
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileNav = document.getElementById('mobileNav');
    if (mobileToggle && mobileNav) {
        mobileToggle.addEventListener('click', () => {
            mobileNav.classList.toggle('hidden');
        });
    }
    
    // Get cart count
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const countEl = document.querySelector('.cart-count');
            if (countEl && data.count !== undefined) {
                countEl.textContent = data.count;
            }
        })
        .catch(error => console.error('Error:', error));
});
// Update wishlist count
function updateWishlistCount() {
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    const countEl = document.querySelector('.wishlist-count');
    if (countEl) {
        countEl.textContent = wishlist.length;
    }
}

// Call on page load and when wishlist changes
document.addEventListener('DOMContentLoaded', updateWishlistCount);

// Override toggleWishlist to update count
const originalToggle = window.toggleWishlist;
window.toggleWishlist = function(productId) {
    originalToggle(productId);
    updateWishlistCount();
};
</script>
@endpush