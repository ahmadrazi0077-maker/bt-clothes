@props(['product'])

@php

    /*
    |--------------------------------------------------------------------------
    | BASIC PRODUCT DATA
    |--------------------------------------------------------------------------
    */

    $productId = $product['id']
        ?? 'mock_' . uniqid();

    $productTitle = $product['title']
        ?? 'Product';

    $productHandle = $product['handle']
        ?? 'product-' . uniqid();

    $productVendor = $product['vendor']
        ?? 'BT Clothes';

    $productAvailable = $product['availableForSale']
        ?? true;


    /*
    |--------------------------------------------------------------------------
    | PRICE
    |--------------------------------------------------------------------------
    */

    $price = '0.00';

    if (
        isset($product['priceRange']['minVariantPrice']['amount'])
    ) {
        $price =
            $product['priceRange']['minVariantPrice']['amount'];

    } elseif (
        isset($product['price'])
    ) {
        $price = $product['price'];
    }


    /*
    |--------------------------------------------------------------------------
    | COMPARE PRICE
    |--------------------------------------------------------------------------
    */

    $comparePrice = null;

    if (
        isset($product['compareAtPriceRange']['minVariantPrice']['amount'])
    ) {
        $comparePrice =
            $product['compareAtPriceRange']['minVariantPrice']['amount'];

    } elseif (
        isset($product['compare_at_price'])
    ) {
        $comparePrice =
            $product['compare_at_price'];
    }


    /*
    |--------------------------------------------------------------------------
    | PRODUCT IMAGE
    |--------------------------------------------------------------------------
    */

    $productImage = null;

    if (
        isset($product['images']['edges'][0]['node']['url'])
    ) {
        $productImage =
            $product['images']['edges'][0]['node']['url'];

    } elseif (
        isset($product['images'][0]['src'])
    ) {
        $productImage =
            $product['images'][0]['src'];

    } elseif (
        isset($product['image']['url'])
    ) {
        $productImage =
            $product['image']['url'];
    }


    /*
    |--------------------------------------------------------------------------
    | VARIANTS
    |--------------------------------------------------------------------------
    |
    | Convert Shopify edges format into simple array.
    |
    */

    $variants = [];

    if (
        isset($product['variants']['edges'])
        && is_array($product['variants']['edges'])
    ) {

        foreach ($product['variants']['edges'] as $edge) {

            if (isset($edge['node'])) {

                $variants[] = $edge['node'];

            }

        }

    } elseif (
        isset($product['variants'])
        && is_array($product['variants'])
    ) {

        $variants = $product['variants'];

    }


    /*
    |--------------------------------------------------------------------------
    | VARIANT LOGIC
    |--------------------------------------------------------------------------
    */

    $variantCount = count($variants);

    $hasVariants = $variantCount > 1;

    $variantId = null;

    if ($variantCount >= 1) {

        $variantId =
            $variants[0]['id']
            ?? $variants[0]['variantId']
            ?? null;

    }


    /*
    |--------------------------------------------------------------------------
    | DISCOUNT
    |--------------------------------------------------------------------------
    */

    $discountPercent = null;

    if (
        $comparePrice
        && (float) $comparePrice > (float) $price
    ) {

        $saved =
            (float) $comparePrice - (float) $price;

        $discountPercent =
            round(
                ($saved / (float) $comparePrice) * 100
            );
    }

@endphp


<article
    class="group relative"
    data-product-id="{{ $productId }}"
>


    {{-- ========================================================= --}}
    {{-- PRODUCT IMAGE --}}
    {{-- ========================================================= --}}

    <div class="relative overflow-hidden bg-gray-100 rounded-xl">

        <a
            href="/product/{{ $productHandle }}"
            aria-label="View {{ $productTitle }}"
        >

            @if($productImage)

                <img
                    src="{{ $productImage }}"
                    alt="{{ $productTitle }}"
                    class="w-full aspect-[4/5] object-cover transition-transform duration-700 group-hover:scale-105"
                    loading="lazy"
                    width="400"
                    height="500"
                >

            @else

                <div
                    class="w-full aspect-[4/5] flex items-center justify-center text-gray-400 text-4xl"
                >
                    👕
                </div>

            @endif

        </a>


        {{-- SALE BADGE --}}

        @if($discountPercent)

            <span
                class="absolute top-3 left-3 z-10 rounded-full bg-red-500 px-3 py-1 text-xs font-bold text-white"
            >
                -{{ $discountPercent }}%
            </span>

        @endif


        {{-- SOLD OUT OVERLAY --}}

        @if(!$productAvailable)

            <div
                class="absolute inset-0 z-10 flex items-center justify-center bg-black/50"
            >

                <span
                    class="rounded-lg bg-black/80 px-4 py-2 text-lg font-bold text-white"
                >
                    Sold Out
                </span>

            </div>

        @endif

    </div>



    {{-- ========================================================= --}}
    {{-- PRODUCT INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="p-4">


        {{-- Vendor --}}

        @if($productVendor)

            <div
                class="text-xs uppercase tracking-wider text-gray-500"
            >
                {{ $productVendor }}
            </div>

        @endif


        {{-- Title --}}

        <h3 class="mt-1 text-sm font-semibold md:text-base">

            <a
                href="/product/{{ $productHandle }}"
                class="line-clamp-2 transition hover:text-gray-600"
            >
                {{ $productTitle }}
            </a>

        </h3>


        {{-- Price --}}

        <div class="mt-2 flex flex-wrap items-center gap-2">

            <span class="text-lg font-bold">

                Rs.
                {{ number_format((float) $price, 0) }}

            </span>


            @if(
                $comparePrice
                && (float) $comparePrice > (float) $price
            )

                <span
                    class="text-sm text-gray-400 line-through"
                >
                    Rs.
                    {{ number_format((float) $comparePrice, 0) }}
                </span>

            @endif

        </div>



        {{-- ========================================================= --}}
        {{-- ACTION BUTTON --}}
        {{-- ========================================================= --}}

        @if(!$productAvailable)


            {{-- SOLD OUT --}}

            <button
                type="button"
                disabled
                class="mt-3 flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-lg border-2 border-gray-300 py-2.5 text-sm font-semibold text-gray-400"
            >

                Sold Out

            </button>


        @elseif($hasVariants)


            {{-- ================================================= --}}
            {{-- MULTIPLE VARIANTS --}}
            {{-- ================================================= --}}
            {{-- NEVER ADD DIRECTLY TO CART --}}
            {{-- GO TO PRODUCT PAGE --}}
            {{-- ================================================= --}}

            <a
                href="/product/{{ $productHandle }}"
                class="mt-3 flex w-full items-center justify-center gap-2 rounded-lg bg-gray-900 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700"
            >

                 {{-- Eye / Options Icon --}}

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    />
                </svg>

                Select Options

            </a>


        @elseif($variantId)


            {{-- ================================================= --}}
            {{-- SINGLE VARIANT --}}
            {{-- ================================================= --}}
            {{-- ✅ DIRECT ADD TO CART --}}
            {{-- ================================================= --}}

          <button type="button" 
        class="add-to-cart-btn w-full mt-3 py-2.5 rounded-lg font-semibold text-sm transition flex items-center justify-center gap-2 bg-gray-900 text-white hover:bg-gray-700"
        data-variant-id="{{ $variantId }}"
        onclick="addToCart(this)">
    🛒 Add to Cart
</button>


        @else


            {{-- ================================================= --}}
            {{-- NO VARIANT DATA --}}
            {{-- ================================================= --}}

            <a
                href="/product/{{ $productHandle }}"
                class="mt-3 flex w-full items-center justify-center gap-2 rounded-lg bg-gray-900 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700"
            >

                View Product

            </a>

        @endif

    </div>

</article> 