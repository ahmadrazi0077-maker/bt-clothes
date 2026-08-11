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
    <script>
        function addToCart(button) {
    const variantId = button.dataset.variantId;
    
    if (!variantId) {
        showToast('❌ Please select a variant');
        return;
    }
    
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '⏳ Adding...';
    button.classList.add('opacity-70');
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            variant_id: variantId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.innerHTML = '✅ Added!';
            button.classList.add('bg-green-600');
            button.classList.remove('bg-gray-900');
            
            // ✅ Update cart count only - NO REDIRECT
            updateCartCount();
            showToast('🛒 Product added to cart!');
            
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
            showToast('❌ ' + (data.message || 'Error adding to cart'));
        }
    })
    .catch(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        button.classList.remove('opacity-70');
        showToast('❌ Error adding to cart');
    });
}

function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const el = document.querySelector('.cart-count');
            if (el) el.textContent = data.count || 0;
        })
        .catch(() => {});
}

function showToast(message) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    if (toast && toastMessage) {
        toastMessage.textContent = message;
        toast.classList.add('show');
        clearTimeout(toast._timeout);
        toast._timeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }
}
    </script>
    
    @stack('scripts')
</body>
</html> 