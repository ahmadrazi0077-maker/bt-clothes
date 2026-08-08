// ============================================
// SHOPIFY CART FUNCTIONS
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    
    // Add to Cart
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const variantId = this.dataset.variantId;
            const productId = this.dataset.productId;
            const quantity = this.closest('.product-item')?.querySelector('.qty-input')?.value || 1;
            
            // Show loading
            const originalText = this.innerHTML;
            this.innerHTML = '⏳ Adding...';
            this.disabled = true;
            
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    variant_id: variantId,
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.innerHTML = '✅ Added!';
                    this.classList.add('bg-green-500', 'text-white', 'border-green-500');
                    
                    showToast('Product added to cart! 🛒');
                    
                    // Update cart count
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount && data.cart?.lines?.edges) {
                        cartCount.textContent = data.cart.lines.edges.length;
                    }
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                        this.classList.remove('bg-green-500', 'text-white', 'border-green-500');
                    }, 2000);
                } else {
                    showToast('Error adding to cart');
                    this.innerHTML = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding to cart');
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
    
    // Toast notification
    function showToast(message) {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.textContent = message;
            toast.classList.add('show');
            clearTimeout(toast._timeout);
            toast._timeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    }
    
    // Global toast function
    window.showToast = showToast;
});

// ===== Add to Cart (AJAX) =====
document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const variantId = this.dataset.variantId;
        const quantity = this.closest('.product-item')?.querySelector('.qty-input')?.value || 1;
        
        if (!variantId) {
            showToast('Error: Product variant not found');
            return;
        }
        
        // Show loading
        const originalText = this.innerHTML;
        this.innerHTML = '⏳ Adding...';
        this.disabled = true;
        
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
                this.innerHTML = '✅ Added!';
                this.classList.add('bg-green-500', 'text-white', 'border-green-500');
                
                showToast('🛒 ' + data.message);
                
                // Update cart count
                const cartCount = document.querySelector('.cart-count');
                if (cartCount && data.itemCount !== undefined) {
                    cartCount.textContent = data.itemCount;
                }
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                    this.classList.remove('bg-green-500', 'text-white', 'border-green-500');
                }, 2000);
            } else {
                showToast('❌ ' + (data.message || 'Error adding to cart'));
                this.innerHTML = originalText;
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('❌ Error adding to cart');
            this.innerHTML = originalText;
            this.disabled = false;
        });
    });
});