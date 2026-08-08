<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BT Clothes')</title>
    <meta name="description" content="@yield('description', 'Timeless clothing for the modern minimalist.')">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-2xl transform translate-y-20 opacity-0 transition-all duration-500 z-50 max-w-sm">
        <span id="toast-message"></span>
    </div>
    
    <!-- ✅ ANNOUNCEMENT BAR -->
   
    <!-- ✅ TEST ANNOUNCEMENT BAR - DIRECT HTML -->
    <div style="background: #1a1a1a; color: white; padding: 10px 20px; text-align: center; position: relative; z-index: 999;">
        <div style="max-width: 1280px; margin: 0 auto; display: flex; align-items: center; justify-content: center; gap: 15px; flex-wrap: wrap;">
            <span style="font-size: 14px;">
                🚚 Free Shipping on orders over $100 | Use code: <strong>WELCOME10</strong> for 10% off
            </span>
            <a href="/collections/all" style="color: white; text-decoration: underline; font-weight: 600; font-size: 14px;">
                Shop Now →
            </a>
            <button onclick="this.parentElement.parentElement.style.display='none'" 
                    style="background: none; border: none; color: white; font-size: 18px; cursor: pointer; position: absolute; right: 20px; top: 50%; transform: translateY(-50%);">
                ✕
            </button>
        </div>
    </div>
    
   
    
    <!-- Header -->
    @include('components.header')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('components.footer')
    
    <!-- Quick View Modal -->
    @include('components.quick-view')
    
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    <script src="{{ asset('js/custom.js') }}"></script>
    @stack('scripts')
    
    <script>
        // ===== Toast Notification (Global) =====
        window.showToast = function(message) {
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
        };
    </script>
</body>
</html>