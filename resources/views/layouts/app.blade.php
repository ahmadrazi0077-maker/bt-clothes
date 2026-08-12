<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BT Clothes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <!-- Toast -->
    <div id="toast" class="fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-2xl transform translate-y-20 opacity-0 transition-all duration-500 z-50 max-w-sm">
        <span id="toast-message"></span>
    </div>
    
    @include('components.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('components.footer')
    
    <!-- ✅ Load JS -->
    <script>
    // ============================================
    // ADD TO CART - WORKING
    // ============================================
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
        
        const token = document.querySelector('meta[name="csrf-token"]');
        
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token ? token.content : '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                variant_id: variantId,
                quantity: 1
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                button.innerHTML = '✅ Added!';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-gray-900');
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
        .catch(error => {
            console.error('Error:', error);
            button.innerHTML = originalText;
            button.disabled = false;
            button.classList.remove('opacity-70');
            showToast('❌ Server error');
        });
    }
    
    function updateCartCount() {
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                const el = document.querySelector('.cart-count');
                if (el) {
                    el.textContent = data.count || 0;
                }
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
    
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
        console.log('✅ JS loaded');
    });
    </script>
    
    @stack('scripts')
</body>
</html>