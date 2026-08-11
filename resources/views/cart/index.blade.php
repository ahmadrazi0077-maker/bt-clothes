@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ✅ Check if clearCart button exists
    const clearCartBtn = document.getElementById('clearCart');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            if (!confirm('Clear all items from cart?')) return;
            
            fetch('/cart/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    // ✅ Check if quantity buttons exist
    const qtyBtns = document.querySelectorAll('.qty-btn');
    if (qtyBtns.length > 0) {
        qtyBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('.cart-item');
                const input = row.querySelector('.qty-input');
                const lineId = input.dataset.lineId || row.dataset.lineId;
                let quantity = parseInt(input.value) || 1;
                
                if (this.textContent.trim() === '−') {
                    if (quantity > 1) quantity--;
                } else {
                    if (quantity < 10) quantity++;
                }
                
                input.value = quantity;
                updateCart(lineId, quantity);
            });
        });
    }
    
    // ✅ Check if remove buttons exist
    const removeBtns = document.querySelectorAll('.remove-item');
    if (removeBtns.length > 0) {
        removeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const lineId = this.dataset.lineId;
                if (!confirm('Remove this item from cart?')) return;
                
                fetch('/cart/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        line_id: lineId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`.cart-item[data-line-id="${lineId}"]`);
                        if (row) row.remove();
                        updateCartSummary();
                        if (data.itemCount === 0) {
                            location.reload();
                        }
                    } else {
                        alert(data.message || 'Error removing item');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error removing item');
                });
            });
        });
    }
    
    // ============================================
    // UPDATE CART SUMMARY
    // ============================================
    
    function updateCartSummary() {
        let subtotal = 0;
        let itemCount = 0;
        
        document.querySelectorAll('.cart-item').forEach(row => {
            const price = parseFloat(row.dataset.price) || 0;
            const qty = parseInt(row.querySelector('.qty-input').value) || 1;
            const total = price * qty;
            
            subtotal += total;
            itemCount += qty;
            
            const totalEl = row.querySelector('.item-total');
            if (totalEl) {
                totalEl.textContent = 'Rs. ' + total.toFixed(0);
            }
        });
        
        const subtotalEl = document.getElementById('subtotal');
        if (subtotalEl) {
            subtotalEl.textContent = 'Rs. ' + subtotal.toFixed(0);
        }
        
        const totalEl = document.getElementById('cartTotal');
        if (totalEl) {
            totalEl.textContent = 'Rs. ' + subtotal.toFixed(0);
        }
        
        const itemCountEl = document.getElementById('cartItemCount');
        if (itemCountEl) {
            itemCountEl.textContent = itemCount + ' items';
        }
        
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = itemCount;
        }
    }
    
    // ============================================
    // UPDATE CART
    // ============================================
    
    function updateCart(lineId, quantity) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                line_id: lineId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartSummary();
            } else {
                alert(data.message || 'Error updating cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating cart');
        });
    }
    
    // ============================================
    // INIT
    // ============================================
    
    // ✅ Update cart summary on load
    updateCartSummary();
});
</script>
@endpush