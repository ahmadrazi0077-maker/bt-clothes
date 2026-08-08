@extends('layouts.app')

@section('title', $product['title'] ?? 'Product')
@section('description', strip_tags($product['description'] ?? ''))

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    @if(isset($product) && $product)
        @php
            // Safely extract product data
            $productId = $product['id'] ?? null;
            $productTitle = $product['title'] ?? 'Product';
            $productHandle = $product['handle'] ?? '';
            $productDescription = $product['descriptionHtml'] ?? $product['description'] ?? '';
            
            // Get price
            $price = '0.00';
            if (isset($product['priceRange']['minVariantPrice']['amount'])) {
                $price = $product['priceRange']['minVariantPrice']['amount'];
            } elseif (isset($product['variants'][0]['price'])) {
                $price = $product['variants'][0]['price'];
            }
            
            // Get compare price
            $comparePrice = null;
            if (isset($product['compareAtPriceRange']['minVariantPrice']['amount'])) {
                $comparePrice = $product['compareAtPriceRange']['minVariantPrice']['amount'];
            } elseif (isset($product['compare_at_price'])) {
                $comparePrice = $product['compare_at_price'];
            }
            
            // Get images
            $images = [];
            if (isset($product['images']['edges'])) {
                foreach ($product['images']['edges'] as $edge) {
                    $images[] = $edge['node']['url'] ?? null;
                }
            } elseif (isset($product['images'])) {
                foreach ($product['images'] as $image) {
                    $images[] = $image['src'] ?? null;
                }
            }
            
            // Get variants
            $variants = [];
            if (isset($product['variants']['edges'])) {
                foreach ($product['variants']['edges'] as $edge) {
                    $variants[] = $edge['node'];
                }
            } elseif (isset($product['variants'])) {
                $variants = $product['variants'];
            }
            
            $productAvailable = $product['availableForSale'] ?? true;
            $productVendor = $product['vendor'] ?? '';
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div>
                <div class="bg-gray-100 rounded-xl overflow-hidden">
                    @if(count($images) > 0 && $images[0])
                        <img 
                            src="{{ $images[0] }}" 
                            alt="{{ $productTitle }}"
                            class="w-full h-96 md:h-[500px] object-cover"
                            id="mainImage"
                        >
                    @else
                        <div class="w-full h-96 md:h-[500px] flex items-center justify-center text-gray-400">
                            <div class="text-center">
                                <div class="text-6xl mb-4">👕</div>
                                No Image Available
                            </div>
                        </div>
                    @endif
                </div>
                
                @if(count($images) > 1)
                    <div class="grid grid-cols-4 gap-3 mt-4">
                        @foreach($images as $image)
                            @if($image)
                                <div class="bg-gray-100 rounded-lg h-20 overflow-hidden cursor-pointer hover:ring-2 hover:ring-gray-900 transition thumbnail">
                                    <img 
                                        src="{{ $image }}" 
                                        alt="{{ $productTitle }}"
                                        class="w-full h-full object-cover"
                                        data-main="{{ $image }}"
                                    >
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Product Info -->
            <div>
                @if($productVendor)
                    <div class="text-sm text-gray-500 uppercase tracking-wider">{{ $productVendor }}</div>
                @endif
                
                <h1 class="text-3xl md:text-4xl font-light mt-1">{{ $productTitle }}</h1>
                
                <div class="flex items-center gap-3 mt-4">
                    <span class="text-3xl font-bold">
                        ${{ number_format((float)$price, 2) }}
                    </span>
                    @if($comparePrice && $comparePrice > $price)
                        <span class="text-gray-400 line-through text-xl">
                            ${{ number_format((float)$comparePrice, 2) }}
                        </span>
                        @php
                            $saved = (float)$comparePrice - (float)$price;
                            $percent = round(($saved / (float)$comparePrice) * 100);
                        @endphp
                        <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full font-bold">
                            Save {{ $percent }}%
                        </span>
                    @endif
                </div>
                
                @if($productDescription)
                    <div class="mt-6 prose prose-gray max-w-none">
                        {!! $productDescription !!}
                    </div>
                @endif
                
                <!-- Variants -->
                @if(count($variants) > 1)
                    <div class="mt-6">
                        <label class="block text-sm font-semibold mb-2">Options</label>
                        <select id="variantSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900">
                            @foreach($variants as $variant)
                                @php
                                    $variantId = $variant['id'] ?? '';
                                    $variantTitle = $variant['title'] ?? 'Default';
                                    $variantPrice = $variant['price']['amount'] ?? $variant['price'] ?? '0.00';
                                @endphp
                                <option value="{{ $variantId }}" data-price="{{ $variantPrice }}">
                                    {{ $variantTitle }} - ${{ number_format((float)$variantPrice, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @elseif(count($variants) == 1)
                    @php
                        $singleVariant = $variants[0] ?? null;
                        $variantId = $singleVariant['id'] ?? null;
                    @endphp
                @endif
                
                <!-- Quantity -->
                <div class="mt-6">
                    <label class="block text-sm font-semibold mb-2">Quantity</label>
                    <div class="qty-controls flex items-center border border-gray-300 rounded-lg w-fit">
                        <button class="qty-decrease px-4 py-2 hover:bg-gray-100 transition">-</button>
                        <input type="number" value="1" min="1" max="10" class="qty-input w-16 text-center border-0 focus:ring-0">
                        <button class="qty-increase px-4 py-2 hover:bg-gray-100 transition">+</button>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-4 mt-8">
                    <button class="add-to-cart flex-1 bg-gray-900 text-white py-4 rounded-lg font-semibold hover:bg-gray-700 transition flex items-center justify-center gap-2
                            @if(!$productAvailable) opacity-50 cursor-not-allowed @endif"
                            data-product-id="{{ $productId }}"
                            data-variant-id="{{ $variantId ?? $variants[0]['id'] ?? '' }}"
                            @if(!$productAvailable) disabled @endif>
                        🛍️ 
                        @if($productAvailable)
                            Add to Cart
                        @else
                            Sold Out
                        @endif
                    </button>
                    <button class="wishlist-btn w-14 h-14 border-2 border-gray-300 rounded-lg hover:border-gray-900 transition flex items-center justify-center text-2xl"
                            data-product-id="{{ $productId }}"
                            onclick="toggleWishlist('{{ $productId }}')">
                        🤍
                    </button>
                </div>
                
                <!-- Trust Badges -->
                <div class="flex flex-wrap gap-6 mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-xl">🚚</span> Free Shipping
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-xl">🔄</span> 30 Day Returns
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-xl">🛡️</span> Secure Checkout
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recommendations -->
        @if(!empty($recommendations))
            @include('components.product-recommendations', ['products' => $recommendations])
        @endif
        
    @else
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🔍</div>
            <h2 class="text-2xl font-light">Product Not Found</h2>
            <p class="text-gray-600 mt-2">The product you're looking for doesn't exist.</p>
            <a href="/products" class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Browse Products
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Thumbnail click
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.addEventListener('click', function() {
                const mainImage = document.getElementById('mainImage');
                const imgSrc = this.querySelector('img')?.dataset.main;
                if (mainImage && imgSrc) {
                    mainImage.src = imgSrc;
                }
            });
        });
        
        // Variant select
        const variantSelect = document.getElementById('variantSelect');
        if (variantSelect) {
            variantSelect.addEventListener('change', function() {
                const price = this.options[this.selectedIndex].dataset.price;
                const priceElement = document.querySelector('.text-3xl.font-bold');
                if (priceElement && price) {
                    priceElement.textContent = '$' + parseFloat(price).toFixed(2);
                }
            });
        }
    });
</script>
@endpush
@endsection