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
(function () {

    window.BTCart = {

        getItems() {
            try {
                return JSON.parse(
                    localStorage.getItem('bt_cart') || '[]'
                );
            } catch (e) {
                return [];
            }
        },

        saveItems(items) {
            localStorage.setItem(
                'bt_cart',
                JSON.stringify(items)
            );
        },

        add(variantId, quantity = 1) {

            let items = this.getItems();

            const existing = items.find(
                item => item.variant_id === variantId
            );

            if (existing) {
                existing.quantity += quantity;
            } else {
                items.push({
                    variant_id: variantId,
                    quantity: quantity
                });
            }

            this.saveItems(items);

            this.updateCount();

            console.log('BT Cart:', items);

            return items;
        },

        updateCount() {

            const items = this.getItems();

            const count = items.reduce(
                (total, item) => total + item.quantity,
                0
            );

            document.querySelectorAll(
                '[data-cart-count]'
            ).forEach(el => {
                el.textContent = count;
            });

            // Optional old cart count element
            document.querySelectorAll(
                '#cart-count'
            ).forEach(el => {
                el.textContent = count;
            });

            return count;
        },

        clear() {
            localStorage.removeItem('bt_cart');
            this.updateCount();
        }

    };

    document.addEventListener('DOMContentLoaded', function () {
        BTCart.updateCount();
    });

})();
</script>
<script>

function addToCart(variantId, quantity = 1) {

    console.log('ADD TO CART CLICKED');
    console.log('Variant ID:', variantId);

    if (!variantId) {
        console.error('Variant ID missing');
        return;
    }

    const items = BTCart.add(
        variantId,
        quantity
    );

    console.log('Cart items:', items);

    showToast('Product added to cart!');

    // IMPORTANT:
    // Do NOT redirect to Shopify here.
}

</script>
<script>

function openShopifyCart() {

    const items = BTCart.getItems();

    console.log('Opening Shopify cart');
    console.log('Items:', items);

    if (!items.length) {
        window.location.href =
            'https://shop.btclothes.com/cart';

        return;
    }

    const cartItems = items
        .map(item => {
            return `${item.variant_id.replace('gid://shopify/ProductVariant/', '')}:${item.quantity}`;
        })
        .join(',');

    const shopifyCartUrl =
        `https://shop.btclothes.com/cart/${cartItems}`;

    console.log(
        'Shopify Cart URL:',
        shopifyCartUrl
    );

    window.location.href = shopifyCartUrl;
}

</script>
    
    @stack('scripts')
</body>
</html> 