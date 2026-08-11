// ============================================
// BT CLOTHES - CART SYSTEM
// ============================================

(function() {
    'use strict';
    
    // ============================================
    // ADD TO CART
    // ============================================
    
    window.addToCart = function(button) {
        const variantId = button.dataset.variantId;
        
        if (!variantId) {
            showToast('❌ Please select a variant');
            return;
        }
        
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '⏳ Adding...';
        button.classList.add('opacity-70');
        
        // ✅ Get CSRF token
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
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // ✅ Update button
                button.innerHTML = '✅ Added!';
                button.classList.add('bg-green-600');
                button.classList.remove('bg-gray-900');
                
                // ✅ Update cart count
                updateCartCount();
                
                // ✅ Show toast
                showToast('🛒 Product added to cart!');
                
                // ✅ Reset button after 2 seconds
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
            console.error('Add to cart error:', error);
            button.innerHTML = originalText;
            button.disabled = false;
            button.classList.remove('opacity-70');
            showToast('❌ Error adding to cart');
        });
    };
    
    // ============================================
    // UPDATE CART COUNT
    // ============================================
    
    window.updateCartCount = function() {
        const token = document.querySelector('meta[name="csrf-token"]');
        
        fetch('/cart/count', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token ? token.content : ''
            }
        })
        .then(response => response.json())
        .then(data => {
            const countEl = document.querySelector('.cart-count');
            if (countEl) {
                countEl.textContent = data.count || 0;
            }
            
            // ✅ Update cart item count on cart page
            const itemCountEl = document.getElementById('cartItemCount');
            if (itemCountEl) {
                itemCountEl.textContent = data.count + ' items';
            }
        })
        .catch(error => {
            console.error('Update cart count error:', error);
        });
    };
    
    // ============================================
    // UPDATE CART (Quantity)
    // ============================================
    
    window.updateCart = function(lineId, quantity) {
        const token = document.querySelector('meta[name="csrf-token"]');
        
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token ? token.content : '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                line_id: lineId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ✅ Reload page to show updated cart
                window.location.reload();
            } else {
                showToast('❌ ' + (data.message || 'Error updating cart'));
            }
        })
        .catch(error => {
            console.error('Update cart error:', error);
            showToast('❌ Error updating cart');
        });
    };
    
    // ============================================
    // REMOVE FROM CART
    // ============================================
    
    window.removeFromCart = function(lineId) {
        if (!confirm('Remove this item from cart?')) {
            return;
        }
        
        const token = document.querySelector('meta[name="csrf-token"]');
        
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token ? token.content : '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                line_id: lineId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ✅ Reload page to show updated cart
                window.location.reload();
            } else {
                showToast('❌ ' + (data.message || 'Error removing item'));
            }
        })
        .catch(error => {
            console.error('Remove from cart error:', error);
            showToast('❌ Error removing item');
        });
    };
    
    // ============================================
    // CLEAR CART
    // ============================================
    
    window.clearCart = function() {
        if (!confirm('Clear all items from cart?')) {
            return;
        }
        
        const token = document.querySelector('meta[name="csrf-token"]');
        
        fetch('/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token ? token.content : '',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                showToast('❌ ' + (data.message || 'Error clearing cart'));
            }
        })
        .catch(error => {
            console.error('Clear cart error:', error);
            showToast('❌ Error clearing cart');
        });
    };
    
    // ============================================
    // TOAST NOTIFICATION
    // ============================================
    
    window.showToast = function(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        
        if (toast && toastMessage) {
            toastMessage.textContent = message;
            toast.classList.add('show');
            
            // Clear previous timeout
            if (window.toastTimeout) {
                clearTimeout(window.toastTimeout);
            }
            
            // Auto hide after 3 seconds
            window.toastTimeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    };
    
    // ============================================
    // QUANTITY CONTROLS (Cart Page)
    // ============================================
    
    function initQuantityControls() {
        document.querySelectorAll('.qty-decrease, .qty-increase').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const row = this.closest('[data-line-id]');
                if (!row) return;
                
                const input = row.querySelector('.qty-input');
                if (!input) return;
                
                const lineId = row.dataset.lineId;
                let quantity = parseInt(input.value) || 1;
                
                if (this.classList.contains('qty-decrease')) {
                    if (quantity > 1) quantity--;
                } else {
                    if (quantity < 10) quantity++;
                }
                
                input.value = quantity;
                updateCart(lineId, quantity);
            });
        });
        
        // ✅ Remove buttons
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const lineId = this.dataset.lineId;
                if (lineId) {
                    removeFromCart(lineId);
                }
            });
        });
    }
    
    // ============================================
    // INITIALIZE
    // ============================================
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🛒 Cart system initialized');
        
        // ✅ Update cart count on page load
        updateCartCount();
        
        // ✅ Initialize quantity controls
        initQuantityControls();
        
        // ✅ Initialize add to cart buttons
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            // Remove existing event listeners to avoid duplicates
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                addToCart(this);
            });
        });
    });
    
})();