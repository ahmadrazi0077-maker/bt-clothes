<div class="quick-view-modal fixed inset-0 bg-black/70 z-[9998] flex items-center justify-center opacity-0 invisible transition-all duration-300 px-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto p-6 md:p-10 relative">
        <button class="quick-view-close absolute top-4 right-4 text-2xl hover:text-gray-600 transition" onclick="closeQuickView()">✕</button>
        
        <div id="quickViewContent" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div class="bg-gray-100 rounded-lg h-80 md:h-96 flex items-center justify-center text-gray-400" id="quickViewImage">
                <div class="text-center">
                    <div class="text-5xl mb-2">👕</div>
                    <p class="text-sm">Loading...</p>
                </div>
            </div>
            
            <!-- Product Info -->
            <div id="quickViewInfo">
                <div class="space-y-4">
                    <div id="quickViewVendor" class="text-sm text-gray-500 uppercase tracking-wider">Loading...</div>
                    <h2 id="quickViewTitle" class="text-2xl font-light">Loading...</h2>
                    
                    <div class="flex items-center gap-3">
                        <span id="quickViewPrice" class="text-2xl font-bold">$0.00</span>
                        <span id="quickViewCompare" class="text-gray-400 line-through text-lg hidden">$0.00</span>
                    </div>
                    
                    <p id="quickViewDescription" class="text-gray-600 text-sm">Loading product details...</p>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2">Quantity</label>
                        <div class="flex items-center border border-gray-300 rounded-lg w-fit">
                            <button class="qty-decrease px-4 py-2 hover:bg-gray-100 transition">-</button>
                            <input type="number" id="quickViewQty" value="1" min="1" max="10" class="w-16 text-center border-0 focus:ring-0">
                            <button class="qty-increase px-4 py-2 hover:bg-gray-100 transition">+</button>
                        </div>
                    </div>
                    
                    <button id="quickViewAddToCart" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition flex items-center justify-center gap-2">
                        🛍️ Add to Cart
                    </button>
                    
                    <button id="quickViewWishlist" class="w-full mt-3 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:border-gray-900 transition flex items-center justify-center gap-2" data-product-id="">
                        🤍 Add to Wishlist
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Recommendations Section -->
<div id="recommendationsContainer" class="mt-8 border-t border-gray-200 pt-8 hidden">
    <h3 class="text-xl font-light text-center mb-6">You May <span class="font-bold">Also Like</span></h3>
    <div id="recommendationsGrid" class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Recommendations will be loaded here -->
    </div>
</div>

<script>
let currentProductId = null;

function openQuickView(productId) {
    currentProductId = productId;
    const modal = document.querySelector('.quick-view-modal');
    if (modal) {
        modal.classList.remove('opacity-0', 'invisible');
        modal.classList.add('opacity-100', 'visible');
        document.body.style.overflow = 'hidden';
        
        // Load product data
        loadQuickViewProduct(productId);
    }
}

function closeQuickView() {
    const modal = document.querySelector('.quick-view-modal');
    if (modal) {
        modal.classList.add('opacity-0', 'invisible');
        modal.classList.remove('opacity-100', 'visible');
        document.body.style.overflow = 'auto';
    }
}

function loadQuickViewProduct(productId) {
    // Show loading state
    document.getElementById('quickViewTitle').textContent = 'Loading...';
    document.getElementById('quickViewPrice').textContent = '$0.00';
    document.getElementById('quickViewAddToCart').dataset.productId = productId;
    document.getElementById('quickViewWishlist').dataset.productId = productId;
    
    fetch(`/api/product/${productId}`)
        .then(response => response.json())
        .then(product => {
            if (product) {
                displayQuickViewProduct(product);
                // Load recommendations
                loadRecommendations(productId);
            }
        })
        .catch(error => {
            console.error('Error loading product:', error);
            document.getElementById('quickViewTitle').textContent = 'Product not found';
        });
}

function displayQuickViewProduct(product) {
    const price = product.priceRange?.minVariantPrice?.amount || '0.00';
    const comparePrice = product.compareAtPriceRange?.minVariantPrice?.amount || null;
    const image = product.images?.edges?.[0]?.node?.url || null;
    const vendor = product.vendor || 'BT Clothes';
    const description = product.description || 'No description available.';
    
    document.getElementById('quickViewTitle').textContent = product.title || 'Product';
    document.getElementById('quickViewVendor').textContent = vendor;
    document.getElementById('quickViewPrice').textContent = `$${parseFloat(price).toFixed(2)}`;
    document.getElementById('quickViewDescription').textContent = description;
    document.getElementById('quickViewAddToCart').dataset.variantId = product.variants?.edges?.[0]?.node?.id || '';
    
    // Compare price
    const compareEl = document.getElementById('quickViewCompare');
    if (comparePrice && comparePrice > price) {
        compareEl.textContent = `$${parseFloat(comparePrice).toFixed(2)}`;
        compareEl.classList.remove('hidden');
    } else {
        compareEl.classList.add('hidden');
    }
    
    // Image
    const imageContainer = document.getElementById('quickViewImage');
    if (image) {
        imageContainer.innerHTML = `<img src="${image}" alt="${product.title}" class="w-full h-full object-cover rounded-lg">`;
    } else {
        imageContainer.innerHTML = `<div class="text-center"><div class="text-5xl mb-2">👕</div><p class="text-sm text-gray-400">No image</p></div>`;
    }
    
    // Wishlist button state
    const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    const wishlistBtn = document.getElementById('quickViewWishlist');
    if (wishlist.includes(product.id)) {
        wishlistBtn.innerHTML = '❤️ Added to Wishlist';
        wishlistBtn.classList.add('text-red-500');
    } else {
        wishlistBtn.innerHTML = '🤍 Add to Wishlist';
        wishlistBtn.classList.remove('text-red-500');
    }
}

function loadRecommendations(productId) {
    const container = document.getElementById('recommendationsContainer');
    const grid = document.getElementById('recommendationsGrid');
    
    fetch(`/api/recommendations/${productId}`)
        .then(response => response.json())
        .then(products => {
            if (products && products.length > 0) {
                container.classList.remove('hidden');
                grid.innerHTML = '';
                products.slice(0, 4).forEach(product => {
                    const card = createRecommendationCard(product);
                    grid.appendChild(card);
                });
            } else {
                container.classList.add('hidden');
            }
        })
        .catch(error => {
            container.classList.add('hidden');
        });
}

function createRecommendationCard(product) {
    const div = document.createElement('div');
    const price = product.priceRange?.minVariantPrice?.amount || '0.00';
    const image = product.images?.edges?.[0]?.node?.url || null;
    
    div.className = 'bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition';
    div.innerHTML = `
        <div class="bg-gray-100 h-40 overflow-hidden">
            ${image ? 
                `<img src="${image}" alt="${product.title}" class="w-full h-full object-cover">` :
                `<div class="w-full h-full flex items-center justify-center text-3xl text-gray-400">👕</div>`
            }
        </div>
        <div class="p-3">
            <h4 class="font-semibold text-sm line-clamp-1">${product.title || 'Product'}</h4>
            <p class="font-bold text-sm mt-1">$${parseFloat(price).toFixed(2)}</p>
            <button onclick="window.location.href='/product/${product.handle}'" 
                    class="w-full mt-2 text-xs bg-gray-900 text-white py-1.5 rounded hover:bg-gray-700 transition">
                View Product
            </button>
        </div>
    `;
    return div;
}

// Quick View Add to Cart
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('quickViewAddToCart').addEventListener('click', function() {
        const variantId = this.dataset.variantId;
        const qty = document.getElementById('quickViewQty').value || 1;
        if (variantId) {
            window.addToCart(variantId, parseInt(qty));
            closeQuickView();
        } else {
            showToast('❌ Product variant not found');
        }
    });
    
    document.getElementById('quickViewWishlist').addEventListener('click', function() {
        const productId = this.dataset.productId;
        if (productId) {
            window.toggleWishlist(productId);
            // Update button state
            const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
            if (wishlist.includes(productId)) {
                this.innerHTML = '❤️ Added to Wishlist';
                this.classList.add('text-red-500');
            } else {
                this.innerHTML = '🤍 Add to Wishlist';
                this.classList.remove('text-red-500');
            }
        }
    });
    
    // Quantity controls
    document.querySelectorAll('.qty-decrease, .qty-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.closest('.flex').querySelector('input');
            let val = parseInt(input.value) || 1;
            if (this.classList.contains('qty-decrease')) {
                if (val > 1) val--;
            } else {
                if (val < 10) val++;
            }
            input.value = val;
        });
    });
});

// Close on outside click
document.addEventListener('click', function(e) {
    const modal = document.querySelector('.quick-view-modal');
    if (e.target === modal) {
        closeQuickView();
    }
});
</script>