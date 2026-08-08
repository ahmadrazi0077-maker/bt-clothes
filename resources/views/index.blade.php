<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"content="width=device-width, initial-scale=1">

    <title>Little Bloom</title>

        @vite(['resources/css/app.css','resources/js/app.js']) 

</head>

<body class="bg-white text-gray-900">

    @include('includes.navbar')

<section class="relative h-[90vh] overflow-hidden">

    {{-- Background Image --}}
    <img
        src="{{ asset('images/hero/hero.jpg') }}"
        alt="Little Bloom Collection"
        class="absolute inset-0 h-full w-full object-cover"
    >

    {{-- Dark Overlay --}}
    <div class="absolute inset-0 bg-black/35"></div>

    {{-- Hero Content --}}
    <div class="relative z-10 mx-auto flex h-full max-w-7xl items-center px-6">

        <div class="max-w-2xl text-white">

            <span
                class="inline-block rounded-full bg-white/20 px-4 py-2 text-sm backdrop-blur-md"
            >
                ✨ New Autumn Collection 2026
            </span>

            <h1
                class="mt-6 text-5xl font-bold leading-tight md:text-7xl"
            >
                Discover

                <span class="block text-pink-300">
                    Premium Fashion
                </span>

                For Every Little Princess
            </h1>

            <p
                class="mt-6 max-w-xl text-lg leading-8 text-gray-200"
            >
                Elegant ready-to-wear clothing for kids and young girls.
                Crafted with comfort, quality, and timeless style.
            </p>

            <div class="mt-10 flex flex-wrap gap-4">

                <a
                    href="/shop"
                    class="rounded-full bg-pink-600 px-8 py-4 text-lg font-semibold text-white transition hover:bg-pink-700"
                >
                    Shop Now
                </a>

                <a
                    href="/collections"
                    class="rounded-full border border-white px-8 py-4 text-lg font-semibold text-white transition hover:bg-white hover:text-black"
                >
                    Explore Collection
                </a>

            </div>

        </div>

    </div>

    {{-- Scroll Indicator --}}
    <div
        class="absolute bottom-10 left-1/2 -translate-x-1/2 text-white animate-bounce"
    >
        ↓
    </div>

</section>

<section class="py-20 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">

            <span class="text-pink-600 uppercase tracking-[4px] text-sm font-semibold">
                Shop By Collection
            </span>

            <h2 class="mt-4 text-4xl md:text-5xl font-bold text-gray-900">
                Find Your Favorite Style
            </h2>

            <p class="mt-4 text-gray-500 max-w-2xl mx-auto">
                Discover beautiful outfits carefully selected for kids and young girls.
            </p>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            {{-- Girls --}}
            <a href="/collections/girls" class="group relative overflow-hidden rounded-3xl">

                <img
                    src="{{ asset('images/collections/girls.jpg') }}"
                    alt="Girls Collection"
                    class="h-[500px] w-full object-cover transition duration-500 group-hover:scale-110">

                <div class="absolute inset-0 bg-black/30"></div>

                <div class="absolute bottom-8 left-8 text-white">

                    <h3 class="text-3xl font-bold">
                        Girls
                    </h3>

                    <p class="mt-2">
                        Shop Collection →
                    </p>

                </div>

            </a>

            {{-- Kids --}}
            <a href="/collections/kids" class="group relative overflow-hidden rounded-3xl">

                <img
                    src="{{ asset('images/collections/kids.jpg') }}"
                    alt="Kids Collection"
                    class="h-[500px] w-full object-cover transition duration-500 group-hover:scale-110">

                <div class="absolute inset-0 bg-black/30"></div>

                <div class="absolute bottom-8 left-8 text-white">

                    <h3 class="text-3xl font-bold">
                        Kids
                    </h3>

                    <p class="mt-2">
                        Shop Collection →
                    </p>

                </div>

            </a>

            {{-- Party Wear --}}
            <a href="/collections/party-wear" class="group relative overflow-hidden rounded-3xl">

                <img
                    src="{{ asset('images/collections/party.jpg') }}"
                    alt="Party Wear"
                    class="h-[500px] w-full object-cover transition duration-500 group-hover:scale-110">

                <div class="absolute inset-0 bg-black/30"></div>

                <div class="absolute bottom-8 left-8 text-white">

                    <h3 class="text-3xl font-bold">
                        Party Wear
                    </h3>

                    <p class="mt-2">
                        Shop Collection →
                    </p>

                </div>

            </a>

        </div>

    </div>

</section>

<section class="py-24 bg-gray-50">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex items-end justify-between mb-14">

            <div>

                <span class="uppercase tracking-[4px] text-pink-600 text-sm">
                    Featured Products
                </span>

                <h2 class="text-5xl font-bold mt-3">
                    Best Sellers
                </h2>

                <p class="text-gray-500 mt-4">
                    Premium outfits loved by our customers.
                </p>

            </div>

            <a
                href="/shop"
                class="hidden md:inline-flex border px-6 py-3 rounded-full hover:bg-black hover:text-white transition"
            >
                View All
            </a>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <x-product-card
                title="Floral Pink Dress"
                price="Rs. 4,999"
                image="images/products/product1.jpg"
                badge="NEW"
            />

            <x-product-card
                title="Elegant Party Wear"
                price="Rs. 5,999"
                image="images/products/product2.jpg"
                badge="SALE"
            />

            <x-product-card
                title="Summer Casual Set"
                price="Rs. 3,499"
                image="images/products/product3.jpg"
            />

            <x-product-card
                title="Princess Collection"
                price="Rs. 6,499"
                image="images/products/product4.jpg"
                badge="HOT"
            />

        </div>

    </div>

</section>


<section class="py-24 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="relative overflow-hidden rounded-3xl">

            {{-- Background Image --}}
            <img
                src="{{ asset('images/banners/promo-banner.jpg') }}"
                alt="Premium Collection"
                class="h-[650px] w-full object-cover"
            >

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/40"></div>

            {{-- Content --}}
            <div class="absolute inset-0 flex items-center">

                <div class="max-w-xl text-white px-10 md:px-16">

                    <span class="uppercase tracking-[4px] text-pink-300 text-sm">
                        Premium Collection
                    </span>

                    <h2 class="mt-5 text-4xl md:text-6xl font-bold leading-tight">
                        Elegant Styles
                        <br>
                        For Every Occasion
                    </h2>

                    <p class="mt-6 text-lg text-gray-200 leading-8">
                        Discover beautiful ready-to-wear outfits for kids and
                        young girls. Designed with comfort, elegance and modern fashion.
                    </p>

                    <a
                        href="/shop"
                        class="inline-flex mt-10 rounded-full bg-pink-600 px-8 py-4 text-white font-semibold hover:bg-pink-700 transition"
                    >
                        Shop Collection →
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


<section class="py-24 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex justify-between items-end mb-14">

            <div>

                <span class="uppercase tracking-[4px] text-pink-600 text-sm">
                    New Arrivals
                </span>

                <h2 class="text-5xl font-bold mt-3">
                    Just Landed
                </h2>

            </div>

            <a
                href="/shop"
                class="border rounded-full px-6 py-3 hover:bg-black hover:text-white transition"
            >
                View All
            </a>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">

            <x-product-slider-card
                title="Pink Princess Dress"
                price="Rs. 4,299"
                image="images/products/product1.jpg"
            />

            <x-product-slider-card
                title="Floral Summer Dress"
                price="Rs. 5,499"
                image="images/products/product2.jpg"
            />

            <x-product-slider-card
                title="Girls Casual Wear"
                price="Rs. 3,999"
                image="images/products/product3.jpg"
            />

            <x-product-slider-card
                title="Luxury Party Wear"
                price="Rs. 6,499"
                image="images/products/product4.jpg"
            />

        </div>

    </div>

</section>
</body>

</html>