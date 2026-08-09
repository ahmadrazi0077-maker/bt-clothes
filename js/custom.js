// ============================================
// ADD TO CART - SIMPLE
// ============================================

function addToCart(button) {
    const variantId = button.dataset.variantId;
    
    if (!variantId) {
        alert('Product variant not found');
        return;
    }
    
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '⏳ Adding...';
    
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
            updateCartCount();
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        } else {
            button.innerHTML = '❌ Failed';
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
            alert(data.message || 'Error adding to cart');
        }
    })
    .catch(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        alert('Error adding to cart');
    });
}

// ============================================
// UPDATE CART COUNT
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
// INIT
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});

