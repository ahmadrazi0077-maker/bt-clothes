<!-- ✅ QUICK ADD MODAL -->
<div id="quickAddModal" class="fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center opacity-0 invisible transition-all duration-300 px-4" style="display:none;">
    <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto p-6 relative transform scale-95 transition-all duration-300">
        <!-- Close Button -->
        <button onclick="closeQuickAdd()" class="absolute top-4 right-4 text-2xl hover:text-gray-600 transition">✕</button>
        
        <!-- Product Info -->
        <div class="flex gap-4 mb-4">
            <div class="w-24 h-24 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden" id="quickAddImage">
                <img src="" alt="" class="w-full h-full object-cover">
            </div>
            <div>
                <h3 class="font-semibold text-sm" id="quickAddTitle">Product Title</h3>
                <div class="text-sm text-gray-500" id="quickAddVendor">Vendor</div>
                <div class="font-bold text-lg mt-1" id="quickAddPrice">Rs. 0</div>
            </div>
        </div>
        
        <!-- Variant Picker -->
        <div id="quickAddVariants" class="space-y-4">
            <p class="text-sm text-gray-500">Loading variants...</p>
        </div>
        
        <!-- Quantity -->
        <div class="mt-4">
            <label class="block text-sm font-semibold mb-2">Quantity</label>
            <div class="qty-controls flex items-center border border-gray-300 rounded-lg w-fit">
                <button type="button" class="qty-decrease px-4 py-2 hover:bg-gray-100 transition">-</button>
                <input type="number" id="quickAddQty" value="1" min="1" max="10" class="w-16 text-center border-0 focus:ring-0">
                <button type="button" class="qty-increase px-4 py-2 hover:bg-gray-100 transition">+</button>
            </div>
        </div>
        
        <!-- Add to Cart Button -->
        <button onclick="quickAddSubmit()" class="w-full mt-6 bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition flex items-center justify-center gap-2">
            🛍️ Add to Cart
        </button>
    </div>
</div>

<script>
// ============================================
// QUICK ADD MODAL - COMPLETE
// ============================================

let quickAddSelectedVariant = null;
let quickAddProductId = null;

function openQuickAdd(productId) {
    console.log('Opening quick add for product:', productId);
    
    const modal = document.getElementById('quickAddModal');
    if (!modal) {
        console.error('Modal not found');
        return;
    }
    
    quickAddProductId = productId;
    
    // Show modal
    modal.style.display = 'flex';
    modal.classList.remove('opacity-0', 'invisible');
    modal.classList.add('opacity-100', 'visible');
    document.body.style.overflow = 'hidden';
    
    // Reset content
    document.getElementById('quickAddTitle').textContent = 'Loading...';
    document.getElementById('quickAddPrice').textContent = 'Rs. 0';
    document.getElementById('quickAddVariants').innerHTML = '<p class="text-sm text-gray-500">Loading variants...</p>';
    
    // Fetch product data
    fetch(`/api/product/${productId}`)
        .then(response => response.json())
        .then(product => {
            console.log('Product loaded:', product);
            displayQuickAdd(product);
        })
        .catch(error => {
            console.error('Error loading product:', error);
            document.getElementById('quickAddTitle').textContent = 'Error loading product';
            document.getElementById('quickAddVariants').innerHTML = '<p class="text-sm text-red-500">Failed to load variants</p>';
        });
}

function displayQuickAdd(product) {
    // Set product info
    document.getElementById('quickAddTitle').textContent = product.title || 'Product';
    document.getElementById('quickAddVendor').textContent = product.vendor || 'BT Clothes';
    
    // Set image
    const image = product.images?.edges?.[0]?.node?.url || null;
    const imgEl = document.getElementById('quickAddImage').querySelector('img');
    if (image) {
        imgEl.src = image;
        imgEl.alt = product.title;
    }
    
    // Set price
    const price = product.priceRange?.minVariantPrice?.amount || '0.00';
    document.getElementById('quickAddPrice').textContent = 'Rs. ' + parseFloat(price).toFixed(0);
    
    // Get variants
    let variants = [];
    if (product.variants?.edges) {
        variants = product.variants.edges.map(e => e.node);
    } else if (product.variants) {
        variants = product.variants;
    }
    
    // Get options
    let options = [];
    if (product.options) {
        options = product.options;
    } else if (variants.length > 1) {
        // Build options from variants
        const optionNames = [];
        variants.forEach(v => {
            if (v.selectedOptions) {
                v.selectedOptions.forEach(opt => {
                    if (!optionNames.includes(opt.name)) {
                        optionNames.push(opt.name);
                    }
                });
            }
        });
        
        optionNames.forEach(name => {
            const values = [];
            variants.forEach(v => {
                if (v.selectedOptions) {
                    v.selectedOptions.forEach(opt => {
                        if (opt.name === name && !values.includes(opt.value)) {
                            values.push(opt.value);
                        }
                    });
                }
            });
            options.push({ name: name, values: values });
        });
    }
    
    // Render variant picker
    const container = document.getElementById('quickAddVariants');
    
    if (options.length > 0 && variants.length > 1) {
        let html = '';
        options.forEach(option => {
            html += `<div class="variant-option mb-4">`;
            html += `<label class="block text-sm font-semibold mb-2">${option.name}</label>`;
            html += `<div class="flex flex-wrap gap-2">`;
            
            option.values.forEach(value => {
                let variantId = null;
                let variantPrice = price;
                let isAvailable = true;
                
                variants.forEach(v => {
                    if (v.selectedOptions) {
                        v.selectedOptions.forEach(opt => {
                            if (opt.name === option.name && opt.value === value) {
                                variantId = v.id;
                                variantPrice = v.price?.amount || v.price || price;
                                isAvailable = v.availableForSale !== false;
                            }
                        });
                    }
                });
                
                const isChecked = option.values.indexOf(value) === 0;
                html += `<button type="button" 
                            class="variant-option-btn px-4 py-2 border rounded-lg text-sm transition 
                                   ${isChecked ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-300 hover:border-gray-900'}
                                   ${!isAvailable ? 'opacity-50 cursor-not-allowed line-through' : ''}"
                            data-variant-id="${variantId}"
                            data-price="${variantPrice}"
                            onclick="selectQuickAddVariant(this)"
                            ${!isAvailable ? 'disabled' : ''}>
                            ${value}
                        </button>`;
            });
            
            html += `</div></div>`;
        });
        container.innerHTML = html;
        
        // Set default variant
        const firstBtn = container.querySelector('.variant-option-btn');
        if (firstBtn) {
            quickAddSelectedVariant = {
                id: firstBtn.dataset.variantId,
                price: firstBtn.dataset.price
            };
            document.getElementById('quickAddPrice').textContent = 'Rs. ' + parseFloat(firstBtn.dataset.price).toFixed(0);
        }
    } else if (variants.length === 1) {
        container.innerHTML = `<p class="text-sm text-gray-500">Single variant available</p>`;
        quickAddSelectedVariant = {
            id: variants[0].id,
            price: variants[0].price?.amount || variants[0].price || price
        };
    } else {
        container.innerHTML = `<p class="text-sm text-gray-500">No variants available</p>`;
        quickAddSelectedVariant = {
            id: product.id,
            price: price
        };
    }
}

function selectQuickAddVariant(button) {
    // Deselect others in same group
    const group = button.closest('.variant-option');
    group.querySelectorAll('.variant-option-btn').forEach(btn => {
        btn.classList.remove('border-gray-900', 'bg-gray-900', 'text-white');
        btn.classList.add('border-gray-300');
    });
    
    // Select this one
    button.classList.remove('border-gray-300');
    button.classList.add('border-gray-900', 'bg-gray-900', 'text-white');
    
    // Update selected variant
    quickAddSelectedVariant = {
        id: button.dataset.variantId,
        price: button.dataset.price
    };
    
    // Update price
    document.getElementById('quickAddPrice').textContent = 'Rs. ' + parseFloat(button.dataset.price).toFixed(0);
}

function quickAddSubmit() {
    if (!quickAddSelectedVariant || !quickAddSelectedVariant.id) {
        showToast('❌ Please select a variant');
        return;
    }
    
    const quantity = document.getElementById('quickAddQty').value || 1;
    const variantId = quickAddSelectedVariant.id;
    const btn = document.querySelector('#quickAddModal .add-to-cart-btn');
    
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.classList.add('opacity-70');
    btn.innerHTML = '⏳ Adding...';
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            variant_id: variantId,
            quantity: parseInt(quantity) || 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = '✅ Added!';
            btn.classList.add('bg-green-600');
            updateCartCount();
            showToast('🛒 Product added to cart!');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.classList.remove('opacity-70', 'bg-green-600');
                closeQuickAdd();
            }, 1500);
        } else {
            btn.innerHTML = '❌ Failed';
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                btn.classList.remove('opacity-70');
            }, 2000);
            showToast('❌ ' + (data.message || 'Error adding to cart'));
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        btn.classList.remove('opacity-70');
        showToast('❌ Error adding to cart');
    });
}

function closeQuickAdd() {
    const modal = document.getElementById('quickAddModal');
    if (!modal) return;
    
    modal.classList.add('opacity-0', 'invisible');
    modal.classList.remove('opacity-100', 'visible');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
    document.body.style.overflow = 'auto';
}

// Close on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('quickAddModal');
    if (e.target === modal) {
        closeQuickAdd();
    }
});

// Quantity controls
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#quickAddModal .qty-controls').forEach(controls => {
        const input = document.getElementById('quickAddQty');
        const decrease = controls.querySelector('.qty-decrease');
        const increase = controls.querySelector('.qty-increase');
        
        if (decrease) {
            decrease.addEventListener('click', function() {
                let val = parseInt(input.value) || 1;
                if (val > 1) input.value = val - 1;
            });
        }
        if (increase) {
            increase.addEventListener('click', function() {
                let val = parseInt(input.value) || 1;
                if (val < 10) input.value = val + 1;
            });
        }
    });
});
</script>

<style>
    #quickAddModal .variant-option-btn {
        transition: all 0.2s ease;
    }
    #quickAddModal .variant-option-btn:active {
        transform: scale(0.95);
    }
</style>