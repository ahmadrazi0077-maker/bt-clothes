@props(['product'])

@php
    // Extract product data safely
    $productId = $product['id'] ?? 'mock_' . rand(1, 100);
    $productTitle = $product['title'] ?? 'Product';
    $productHandle = $product['handle'] ?? 'product-' . rand(1, 100);
    $productVendor = $product['vendor'] ?? 'Sanctuary Flow';
    $productAvailable = $product['availableForSale'] ?? true;
    
    // Get product price
    $price = '0.00';
    if (isset($product['priceRange']['minVariantPrice']['amount'])) {
        $price = $product['priceRange']['minVariantPrice']['amount'];
    } elseif (isset($product['price'])) {
        $price = $product['price'];
    } elseif (isset($product['variants'][0]['price'])) {
        $price = $product['variants'][0]['price'];
    }
    
    // Get compare at price
    $comparePrice = null;
    if (isset($product['compareAtPriceRange']['minVariantPrice']['amount'])) {
        $comparePrice = $product['compareAtPriceRange']['minVariantPrice']['amount'];
    } elseif (isset($product['compare_at_price'])) {
        $comparePrice = $product['compare_at_price'];
    }
    
    // Get product image
    $productImage = null;
    if (isset($product['images']['edges'][0]['node']['url'])) {
        $productImage = $product['images']['edges'][0]['node']['url'];
    } elseif (isset($product['images'][0]['src'])) {
        $productImage = $product['images'][0]['src'];
    } elseif (isset($product['image']['url'])) {
        $productImage = $product['image']['url'];
    }
    
    // Get variant ID
    $variantId = null;
    if (isset($product['variants']['edges'][0]['node']['id'])) {
        $variantId = $product['variants']['edges'][0]['node']['id'];
    } elseif (isset($product['variants'][0]['id'])) {
        $variantId = $product['variants'][0]['id'];
    }
@endphp

<div class="product-item bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
    <div class="relative overflow-hidden bg-gray-100">
        @if($productImage)
            <img 
                src="{{ $productImage }}" 
                alt="{{ $productTitle }}"
                class="w-full aspect-[4/5] object-cover transition-transform duration-600 group-hover:scale-105"
                loading="lazy"
                width="400"
                height="500"
            >
        @else
            <div class="w-full aspect-[4/5] flex items-center justify-center text-gray-400 text-sm">
                <div class="text-center">
                    <div class="text-4xl mb-2">👕</div>
                    No Image
                </div>
            </div>
        @endif
        
        <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300">
            <button class="wishlist-btn w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-lg hover:bg-gray-900 hover:text-white transition" 
                    data-product-id="{{ $productId }}" 
                    onclick="toggleWishlist('{{ $productId }}')">
                🤍
            </button>
            <button class="quick-view-btn w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-lg hover:bg-gray-900 hover:text-white transition" 
                    data-product-id="{{ $productId }}">
                👁️
            </button>
        </div>
        
        @if($comparePrice && $comparePrice > $price)
            <span class="absolute top-3 left-3 bg-red-500 text-white text-xs px-3 py-1 rounded-full font-bold uppercase">
                Sale
            </span>
        @endif
        
        @if(!$productAvailable)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="text-white font-bold text-lg px-4 py-2 bg-black/70 rounded-lg">
                    Sold Out
                </span>
            </div>
        @endif
    </div>
    
    <div class="p-4">
        @if($productVendor)
            <div class="text-xs text-gray-500 uppercase tracking-wider">{{ $productVendor }}</div>
        @endif
        
        <h3 class="font-semibold mt-1">
            <a href="/product/{{ $productHandle }}" class="hover:text-gray-600 transition line-clamp-2">
                {{ $productTitle }}
            </a>
        </h3>
        
        <div class="flex items-center gap-2 mt-2">
            <span class="font-bold text-lg">
                ${{ number_format((float)$price, 2) }}
            </span>
            @if($comparePrice && $comparePrice > $price)
                <span class="text-gray-400 line-through text-sm">
                    ${{ number_format((float)$comparePrice, 2) }}
                </span>
                @php
                    $saved = (float)$comparePrice - (float)$price;
                    $percent = round(($saved / (float)$comparePrice) * 100);
                @endphp
                <span class="text-xs text-green-600 font-semibold">
                    -{{ $percent }}%
                </span>
            @endif
        </div>
        
        <button class="add-to-cart w-full mt-3 py-2 border-2 border-gray-900 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition flex items-center justify-center gap-2
                @if(!$productAvailable) opacity-50 cursor-not-allowed @endif"
                data-product-id="{{ $productId }}"
                data-variant-id="{{ $variantId }}"
                @if(!$productAvailable) disabled @endif>
            🛍️ 
            @if($productAvailable)
                Add to Cart
            @else
                Sold Out
            @endif
        </button>
    </div>
</div>