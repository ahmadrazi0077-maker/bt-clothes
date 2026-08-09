@extends('layouts.app')

@section('title', $product['title'] ?? 'Product')
@section('description', strip_tags($product['description'] ?? ''))

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    @if(isset($product) && $product)
        @php
            // Extract product data
            $productId = $product['id'] ?? null;
            $productTitle = $product['title'] ?? 'Product';
            $productHandle = $product['handle'] ?? '';
            $productDescription = $product['descriptionHtml'] ?? $product['description'] ?? '';
            
            // Get price
            $price = '0.00';
            if (isset($product['priceRange']['minVariantPrice']['amount'])) {
                $price = $product['priceRange']['minVariantPrice']['amount'];
            }
            
            // Get compare price
            $comparePrice = null;
            if (isset($product['compareAtPriceRange']['minVariantPrice']['amount'])) {
                $comparePrice = $product['compareAtPriceRange']['minVariantPrice']['amount'];
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
            
            // Get options
            $options = [];
            if (isset($product['options'])) {
                $options = $product['options'];
            } elseif (!empty($variants) && count($variants) > 1) {
                $optionNames = [];
                foreach ($variants as $v) {
                    if (isset($v['selectedOptions'])) {
                        foreach ($v['selectedOptions'] as $opt) {
                            if (!in_array($opt['name'], $optionNames)) {
                                $optionNames[] = $opt['name'];
                            }
                        }
                    }
                }
                foreach ($optionNames as $name) {
                    $values = [];
                    foreach ($variants as $v) {
                        if (isset($v['selectedOptions'])) {
                            foreach ($v['selectedOptions'] as $opt) {
                                if ($opt['name'] === $name && !in_array($opt['value'], $values)) {
                                    $values[] = $opt['value'];
                                }
                            }
                        }
                    }
                    $options[] = ['name' => $name, 'values' => $values];
                }
            }
            
            $hasVariants = count($variants) > 1;
            $productAvailable = $product['availableForSale'] ?? true;
            $productVendor = $product['vendor'] ?? '';
            $defaultVariantId = $variants[0]['id'] ?? null;
            $defaultVariantPrice = $variants[0]['price']['amount'] ?? $price;
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- ========== LEFT: PRODUCT IMAGES ========== -->
            <div>
                <div class="bg-gray-100 rounded-xl overflow-hidden">
                    @if(count($images) > 0 && $images[0])
                        <img src="{{ $images[0] }}" 
                             alt="{{ $productTitle }}"
                             class="w-full h-96 md:h-[500px] object-cover"
                             id="mainImage">
                    @else
                        <div class="w-full h-96 md:h-[500px] flex items-center justify-center text-gray-400 text-4xl">👕</div>
                    @endif
                </div>
                @if(count($images) > 1)
                    <div class="grid grid-cols-4 gap-3 mt-4">
                        @foreach($images as $image)
                            @if($image)
                                <div class="bg-gray-100 rounded-lg h-20 overflow-hidden cursor-pointer hover:ring-2 hover:ring-gray-900 transition thumbnail">
                                    <img src="{{ $image }}" alt="{{ $productTitle }}" class="w-full h-full object-cover" data-main="{{ $image }}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- ========== RIGHT: PRODUCT INFO ========== -->
            <div>
                @if($productVendor)
                    <div class="text-sm text-gray-500 uppercase tracking-wider">{{ $productVendor }}</div>
                @endif
                
                <h1 class="text-3xl md:text-4xl font-light mt-1">{{ $productTitle }}</h1>
                
                <div class="flex items-center gap-3 mt-4">
                    <span class="text-3xl font-bold" id="productPrice">Rs. {{ number_format((float)$price, 0) }}</span>
                    @if($comparePrice && $comparePrice > $price)
                        <span class="text-gray-400 line-through text-xl" id="productComparePrice">Rs. {{ number_format((float)$comparePrice, 0) }}</span>
                        @php
                            $saved = (float)$comparePrice - (float)$price;
                            $percent = round(($saved / (float)$comparePrice) * 100);
                        @endphp
                        <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full font-bold">-{{ $percent }}%</span>
                    @endif
                </div>
                
                @if($productDescription)
                    <div class="mt-6 prose prose-gray max-w-none">
                        {!! $productDescription !!}
                    </div>
                @endif
                
                <!-- ========== VARIANTS ========== -->
                @if($hasVariants && $productAvailable)
                    <div class="mt-6 space-y-4" id="variantPicker">
                        @foreach($options as $option)
                            <div>
                                <label class="block text-sm font-semibold mb-2">{{ $option['name'] }}</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($option['values'] as $value)
                                        @php
                                            $variantId = null;
                                            $variantPrice = $price;
                                            $variantAvailable = true;
                                            foreach ($variants as $v) {
                                                if (isset($v['selectedOptions'])) {
                                                    foreach ($v['selectedOptions'] as $opt) {
                                                        if ($opt['name'] == $option['name'] && $opt['value'] == $value) {
                                                            $variantId = $v['id'];
                                                            $variantPrice = $v['price']['amount'] ?? $v['price'] ?? $price;
                                                            $variantAvailable = $v['availableForSale'] ?? true;
                                                            break 2;
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <button type="button"
                                                class="variant-btn px-4 py-2 border rounded-lg text-sm transition
                                                       {{ $loop->first ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-300 hover:border-gray-900' }}
                                                       {{ !$variantAvailable ? 'opacity-50 cursor-not-allowed line-through' : '' }}"
                                                data-variant-id="{{ $variantId }}"
                                                data-price="{{ $variantPrice }}"
                                                {{ !$variantAvailable ? 'disabled' : '' }}
                                                onclick="selectVariant(this)">
                                            {{ $value }}
                                            @if(!$variantAvailable)
                                                (Sold)
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <!-- ========== QUANTITY ========== -->
                <div class="mt-6">
                    <label class="block text-sm font-semibold mb-2">Quantity</label>
                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden w-fit">
                        <button type="button" 
                                class="qty-btn px-4 py-2 hover:bg-gray-100 transition text-xl font-bold cursor-pointer bg-gray-50"
                                onclick="updateQuantity(-1)">
                            −
                        </button>
                        <input type="text" 
                               id="qtyInput" 
                               value="1" 
                               class="qty-input w-14 text-center border-0 focus:ring-0 text-lg font-semibold bg-white"
                               readonly>
                        <button type="button" 
                                class="qty-btn px-4 py-2 hover:bg-gray-100 transition text-xl font-bold cursor-pointer bg-gray-50"
                                onclick="updateQuantity(1)">
                            +
                        </button>
                    </div>
                </div>
                
                <!-- ========== ADD TO CART ========== -->
                @if($productAvailable)
                    <button class="add-to-cart-btn w-full mt-6 bg-gray-900 text-white py-4 rounded-lg font-semibold hover:bg-gray-700 transition flex items-center justify-center gap-2"
                            data-variant-id="{{ $defaultVariantId }}"
                            onclick="addToCartProduct(this)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Add to Cart</span>
                    </button>
                @else
                    <button class="w-full mt-6 py-4 border-2 border-gray-300 rounded-lg font-semibold text-gray-400 cursor-not-allowed flex items-center justify-center gap-2" disabled>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Sold Out
                    </button>
                @endif
                
                <!-- ========== TRUST BADGES ========== -->
                <div class="flex flex-wrap gap-6 mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-2 text-sm text-gray-600"><span class="text-xl">🚚</span> Free Shipping</div>
                    <div class="flex items-center gap-2 text-sm text-gray-600"><span class="text-xl">🔄</span> 30 Day Returns</div>
                    <div class="flex items-center gap-2 text-sm text-gray-600"><span class="text-xl">🛡️</span> Secure Checkout</div>
                </div>
            </div>
        </div>
        
        <!-- ========== RECOMMENDATIONS ========== -->
        @if(!empty($recommendations))
            @include('components.product-recommendations', ['products' => $recommendations])
        @endif
        
    @else
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🔍</div>
            <h2 class="text-2xl font-light">Product Not Found</h2>
            <a href="/products" class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                Browse Products
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
// ============================================
// QUANTITY - WORKING
// ============================================

function updateQuantity(change) {
    const input = document.getElementById('qtyInput');
    if (!input) return;
    
    let val = parseInt(input.value) || 1;
    val = val + change;
    
    if (val < 1) val = 1;
    if (val > 10) val = 10;
    
    input.value = val;
}

// ============================================
// VARIANT SELECTION
// ============================================

function selectVariant(button) {
    const group = button.closest('div');
    group.querySelectorAll('.variant-btn').forEach(btn => {
        btn.classList.remove('border-gray-900', 'bg-gray-900', 'text-white');
        btn.classList.add('border-gray-300');
    });
    
    button.classList.remove('border-gray-300');
    button.classList.add('border-gray-900', 'bg-gray-900', 'text-white');
    
    const price = button.dataset.price;
    const priceDisplay = document.getElementById('productPrice');
    if (priceDisplay && price) {
        priceDisplay.textContent = 'Rs. ' + parseFloat(price).toFixed(0);
    }
    
    const addBtn = document.querySelector('.add-to-cart-btn');
    if (addBtn) {
        addBtn.dataset.variantId = button.dataset.variantId;
    }
}

// ============================================
// ADD TO CART
// ============================================

function addToCartProduct(button) {
    const variantId = button.dataset.variantId;
    const qtyInput = document.getElementById('qtyInput');
    const quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;
    
    if (!variantId) {
        alert('❌ Please select a variant');
        return;
    }
    
    const originalText = button.innerHTML;
    button.disabled = true;
    button.classList.add('opacity-70');
    button.innerHTML = '⏳ Adding...';
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            variant_id: variantId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.innerHTML = '✅ Added!';
            button.classList.add('bg-green-600');
            updateCartCount();
            alert('🛒 Product added to cart!');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.classList.remove('opacity-70', 'bg-green-600');
                button.classList.add('bg-gray-900');
            }, 2000);
        } else {
            button.innerHTML = '❌ Failed';
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                button.classList.remove('opacity-70');
            }, 2000);
            alert('❌ ' + (data.message || 'Error adding to cart'));
        }
    })
    .catch(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        button.classList.remove('opacity-70');
        alert('❌ Error adding to cart');
    });
}

// ============================================
// CART COUNT
// ============================================

function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const countEl = document.querySelector('.cart-count');
            if (countEl) {
                countEl.textContent = data.count || 0;
            }
        })
        .catch(() => {});
}

// ============================================
// THUMBNAILS
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.addEventListener('click', function() {
            const mainImage = document.getElementById('mainImage');
            const imgSrc = this.querySelector('img')?.dataset.main;
            if (mainImage && imgSrc) {
                mainImage.src = imgSrc;
            }
        });
    });
    
    // Set default quantity
    const qtyInput = document.getElementById('qtyInput');
    if (qtyInput) {
        qtyInput.value = 1;
    }
    
    updateCartCount();
});
</script>
@endpush
@endsection