<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BT Clothes')</title>
    
    <!-- ✅ Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- ✅ Custom CSS - Direct inline -->
    <style>
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
        }
        .product-card img {
            transition: transform 0.5s ease;
        }
        .product-card:hover img {
            transform: scale(1.05);
        }
        .add-to-cart-btn {
            transition: all 0.3s ease;
        }
        .add-to-cart-btn:active {
            transform: scale(0.95);
        }
        #toast.show {
            transform: translateY(0) !important;
            opacity: 1 !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Toast -->
    <div id="toast" class="fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-2xl transform translate-y-20 opacity-0 transition-all duration-500 z-50 max-w-sm">
        <span id="toast-message"></span>
    </div>
    
    <!-- Announcement Bar -->
    <div style="background: #1a1a1a; color: white; padding: 10px 20px; text-align: center; position: relative; z-index: 999;">
        <div style="max-width: 1280px; margin: 0 auto; display: flex; align-items: center; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <span style="font-size: 14px;">🚚 Free Shipping on orders over Rs. 2500 | Use code: <strong>WELCOME10</strong> for 10% off</span>
            <a href="/collections/all" style="color: white; text-decoration: underline; font-weight: 600; font-size: 14px;">Shop Now →</a>
            <button onclick="this.parentElement.parentElement.style.display='none'" style="background: none; border: none; color: white; font-size: 18px; cursor: pointer; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);">✕</button>
        </div>
    </div>
    
    @include('components.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('components.footer')
    @include('components.quick-view')
    @include('components.quick-add-modal')
    
    <!-- ✅ All JavaScript -->
  
        <<script>
async function addToShopifyCart(variantId, quantity = 1) {
    try {
        const existingCartId = localStorage.getItem('shopify_cart_id');

        console.log('Existing cart ID:', existingCartId);

        const response = await fetch('/cart/add', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            body: JSON.stringify({
                variant_id: variantId,
                quantity: quantity,
                cart_id: existingCartId
            })
        });

        const data = await response.json();

        console.log('Shopify cart response:', data);

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Unable to add product');
        }

        // Save the SAME Shopify cart ID
        if (data.cart_id) {
            localStorage.setItem('shopify_cart_id', data.cart_id);
            console.log('Saved Shopify Cart ID:', data.cart_id);
        }

        // Update navbar cart count
        if (typeof updateCartCount === 'function') {
            updateCartCount(data.itemCount);
        }

        // Don't redirect
        if (typeof showToast === 'function') {
            showToast('Product added to cart!');
        }

        return data;

    } catch (error) {
        console.error('Add to Shopify cart error:', error);

        if (typeof showToast === 'function') {
            showToast(error.message || 'Unable to add product', 'error');
        }
    }
}
</script>
    
    @stack('scripts')
</body>
</html> 