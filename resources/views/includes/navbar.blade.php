<div class="bg-pink-600 text-white text-sm">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex h-10 items-center justify-center">

            <p class="font-medium tracking-wide">
                🚚 FREE DELIVERY ON ORDERS ABOVE RS. 5,000
            </p>

        </div>
    </div>
</div>
<header
    x-data="{ open: false }"
    class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200"
>

<div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4">

    {{-- Logo --}}
    <a href="/" class="text-3xl font-bold tracking-wide">

        Little<span class="text-pink-600">Bloom</span>

    </a>

    {{-- Desktop Menu --}}
    <nav class="hidden lg:flex items-center gap-8">

        <a href="/" class="font-medium hover:text-pink-600 transition">
            Home
        </a>

        <a href="/shop" class="font-medium hover:text-pink-600 transition">
            Shop
        </a>

        <a href="/collections" class="font-medium hover:text-pink-600 transition">
            Collections
        </a>

        <a href="/new-arrivals" class="font-medium hover:text-pink-600 transition">
            New Arrivals
        </a>

        <a href="/sale" class="font-medium hover:text-pink-600 transition">
            Sale
        </a>

        <a href="/about" class="font-medium hover:text-pink-600 transition">
            About
        </a>

        <a href="/contact" class="font-medium hover:text-pink-600 transition">
            Contact
        </a>

    </nav>

    {{-- Icons --}}
    <div class="hidden lg:flex items-center gap-5">

        {{-- Search --}}
        <button class="hover:text-pink-600 transition" aria-label="Search">

            🔍

        </button>

        {{-- Wishlist --}}
        <button class="hover:text-pink-600 transition" aria-label="Wishlist">

            🤍

        </button>

        {{-- Cart --}}
        <button
            class="relative hover:text-pink-600 transition"
            aria-label="Shopping Cart"
        >

            🛍️

            <span
                class="absolute -top-2 -right-3 flex h-5 w-5 items-center justify-center rounded-full bg-pink-600 text-xs text-white"
            >
                0
            </span>

        </button>

    </div>

    {{-- Mobile Button --}}
    <button
        @click="open=!open"
        class="lg:hidden text-3xl"
    >
        ☰
    </button>

</div>

{{-- Mobile Menu --}}

<div
    x-show="open"
    x-transition
    class="border-t bg-white lg:hidden"
>

    <nav class="flex flex-col p-6 space-y-5">

        <a href="/">Home</a>

        <a href="/shop">Shop</a>

        <a href="/collections">Collections</a>

        <a href="/new-arrivals">New Arrivals</a>

        <a href="/sale">Sale</a>

        <a href="/about">About</a>

        <a href="/contact">Contact</a>

    </nav>

</div>

</header>