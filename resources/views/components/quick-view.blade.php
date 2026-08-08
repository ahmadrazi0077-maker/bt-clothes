<div class="quick-view-modal fixed inset-0 bg-black/70 z-[9998] flex items-center justify-center opacity-0 invisible transition-all duration-300 px-4">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto p-6 md:p-10 relative">
        <button class="quick-view-close absolute top-4 right-4 text-2xl hover:text-gray-600 transition">✕</button>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gray-100 rounded-lg h-80 md:h-96 flex items-center justify-center text-gray-400">
                Product Image
            </div>
            
            <div>
                <h2 class="text-2xl font-light">Product Name</h2>
                <div class="text-yellow-400 mt-2">⭐⭐⭐⭐½ <span class="text-gray-500 text-sm">(4.5)</span></div>
                <div class="flex items-center gap-3 mt-3">
                    <span class="text-2xl font-bold">$89.00</span>
                    <span class="text-gray-400 line-through">$120.00</span>
                </div>
                <p class="text-gray-600 mt-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p>
                
                <div class="mt-6">
                    <label class="block text-sm font-semibold mb-2">Size</label>
                    <div class="flex items-center gap-3">
                        <select class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                            <option>Select Size</option>
                            <option>XS</option>
                            <option>S</option>
                            <option>M</option>
                            <option>L</option>
                            <option>XL</option>
                        </select>
                        <button class="size-guide-btn text-gray-900 font-semibold hover:underline">📏 Size Guide</button>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="block text-sm font-semibold mb-2">Quantity</label>
                    <div class="qty-controls flex items-center border border-gray-300 rounded-lg w-fit">
                        <button class="qty-decrease px-4 py-2 hover:bg-gray-100 transition">-</button>
                        <input type="number" value="1" min="1" max="10" class="w-16 text-center border-0 focus:ring-0">
                        <button class="qty-increase px-4 py-2 hover:bg-gray-100 transition">+</button>
                    </div>
                </div>
                
                <button class="add-to-cart w-full mt-6 bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition flex items-center justify-center gap-2">
                    🛍️ Add to Cart
                </button>
                
                <button class="wishlist-btn w-full mt-3 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:border-gray-900 transition flex items-center justify-center gap-2" data-product-id="quick-view-product" onclick="toggleWishlist('quick-view-product')">
                    🤍 Add to Wishlist
                </button>
            </div>
        </div>
    </div>
</div>