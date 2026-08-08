<div class="announcement-bar" style="background: #1a1a1a; color: white; padding: 10px 20px; position: relative; z-index: 999; border-bottom: 1px solid #333;">
    <div style="max-width: 1280px; margin: 0 auto; display: flex; align-items: center; justify-content: center; gap: 10px 20px; flex-wrap: wrap; padding-right: 40px;">
        <!-- Message -->
        <div style="display: flex; align-items: center; gap: 8px; font-size: 14px;">
            <span style="font-size: 18px;">🎉</span>
            <span>
                Free Shipping on orders over $100 | Use code: <strong style="color: #ffd700;">WELCOME10</strong> for 10% off
            </span>
        </div>
        
        <!-- CTA Link -->
        <a href="/collections/all" style="color: white; font-weight: 600; font-size: 13px; text-decoration: underline; text-underline-offset: 2px; white-space: nowrap;">
            Shop Now →
        </a>
        
        <!-- Close Button -->
        <button onclick="this.closest('.announcement-bar').style.display='none'; localStorage.setItem('announcement_closed', 'true');" 
                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: rgba(255,255,255,0.5); font-size: 18px; cursor: pointer; padding: 5px;">
            ✕
        </button>
    </div>
</div>

<style>
    .announcement-bar {
        animation: slideDown 0.5s ease-out;
    }
    
    @keyframes slideDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @media (max-width: 640px) {
        .announcement-bar {
            padding: 8px 15px;
        }
        .announcement-bar [style*="font-size: 14px"] {
            font-size: 12px;
        }
    }
</style>

<script>
    // Check if user has closed the announcement before
    document.addEventListener('DOMContentLoaded', function() {
        const isClosed = localStorage.getItem('announcement_closed');
        const bar = document.querySelector('.announcement-bar');
        
        if (isClosed === 'true' && bar) {
            bar.style.display = 'none';
        }
    });
</script>