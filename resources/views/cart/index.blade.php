@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')

<div class="container mx-auto px-4 py-12">

    <h1 class="text-3xl font-light mb-8">
        Shopping Cart
    </h1>

    @if(!empty($cartItems))

        <div class="space-y-4">

            @foreach($cartItems as $item)

                @php

                    $node = $item['node'] ?? [];

                    $merchandise = $node['merchandise'] ?? [];

                    $product = $merchandise['product'] ?? [];

                    $image = null;

                    if (
                        !empty($product['images']['edges'][0]['node']['url'])
                    ) {
                        $image =
                            $product['images']['edges'][0]['node']['url'];
                    }

                    $productTitle =
                        $product['title']
                        ?? $merchandise['title']
                        ?? 'Product';

                    $price = (float) (
                        $merchandise['price']['amount']
                        ?? 0
                    );

                    $currency =
                        $merchandise['price']['currencyCode']
                        ?? 'PKR';

                    $quantity =
                        (int) ($node['quantity'] ?? 1);

                    $lineTotal =
                        $price * $quantity;

                    $lineId =
                        $node['id'] ?? '';

                @endphp

                <div
                    class="flex flex-col md:flex-row md:items-center gap-4 p-4 border border-gray-200 rounded-xl"
                    data-line-id="{{ $lineId }}"
                >

                    {{-- Product image --}}
                    <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">

                        @if($image)

                            <img
                                src="{{ $image }}"
                                alt="{{ $productTitle }}"
                                class="w-full h-full object-cover"
                            >

                        @else

                            <div class="w-full h-full flex items-center justify-center text-3xl">
                                👕
                            </div>

                        @endif

                    </div>


                    {{-- Product info --}}
                    <div class="flex-1">

                        <h3 class="font-semibold text-lg">
                            {{ $productTitle }}
                        </h3>

                        @if(!empty($merchandise['title']) && $merchandise['title'] !== 'Default Title')

                            <p class="text-sm text-gray-500">
                                {{ $merchandise['title'] }}
                            </p>

                        @endif

                        <p class="mt-1 text-gray-600">
                            {{ $currency }}
                            {{ number_format($price, 2) }}
                        </p>

                        <button
                            type="button"
                            class="remove-item mt-2 text-sm text-red-500 hover:text-red-700"
                            data-line-id="{{ $lineId }}"
                        >
                            Remove
                        </button>

                    </div>


                    {{-- Quantity --}}
                    <div class="flex items-center">

                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">

                            <button
                                type="button"
                                class="qty-decrease px-4 py-2 bg-gray-50 hover:bg-gray-100"
                                data-line-id="{{ $lineId }}"
                            >
                                −
                            </button>

                            <input
                                type="text"
                                class="qty-input w-12 text-center border-0 bg-white"
                                value="{{ $quantity }}"
                                readonly
                            >

                            <button
                                type="button"
                                class="qty-increase px-4 py-2 bg-gray-50 hover:bg-gray-100"
                                data-line-id="{{ $lineId }}"
                            >
                                +
                            </button>

                        </div>

                    </div>


                    {{-- Total --}}
                    <div class="text-right min-w-[120px]">

                        <p class="font-bold text-lg">
                            {{ $currency }}
                            {{ number_format($lineTotal, 2) }}
                        </p>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- Cart summary --}}
        <div class="mt-10 flex justify-end">

            <div class="w-full md:w-96 border border-gray-200 rounded-xl p-6">

                <div class="flex justify-between text-lg font-semibold">

                    <span>
                        Total
                    </span>

                    <span>
                        PKR {{ number_format((float) $subtotal, 2) }}
                    </span>

                </div>

                <p class="text-sm text-gray-500 mt-2">
                    {{ $itemCount }} item(s)
                </p>

                <a
                    href="{{ route('checkout') }}"
                    class="mt-6 block text-center bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition"
                >
                    Proceed to Checkout →
                </a>

            </div>

        </div>

    @else

        {{-- Empty cart --}}
        <div class="text-center py-20">

            <div class="text-6xl mb-6">
                🛒
            </div>

            <h2 class="text-2xl font-light">
                Your cart is empty
            </h2>

            <p class="text-gray-500 mt-2">
                Looks like you haven't added anything yet.
            </p>

            <a
                href="{{ route('products') }}"
                class="inline-block mt-6 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition"
            >
                Start Shopping
            </a>

        </div>

    @endif

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const csrfMeta =
        document.querySelector('meta[name="csrf-token"]');

    const csrfToken =
        csrfMeta ? csrfMeta.getAttribute('content') : '';


    /*
    |--------------------------------------------------------------------------
    | Update quantity
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll(
        '.qty-decrease, .qty-increase'
    ).forEach(function (button) {

        button.addEventListener('click', async function () {

            const row =
                this.closest('[data-line-id]');

            if (!row) return;

            const lineId =
                row.getAttribute('data-line-id');

            const input =
                row.querySelector('.qty-input');

            let quantity =
                parseInt(input.value, 10) || 1;


            if (
                this.classList.contains('qty-decrease')
            ) {

                quantity--;

            } else {

                quantity++;

            }


            if (quantity < 1) {
                quantity = 1;
            }

            if (quantity > 10) {
                quantity = 10;
            }


            input.value = quantity;


            try {

                const response =
                    await fetch('{{ route('cart.update') }}', {

                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },

                        body: JSON.stringify({
                            line_id: lineId,
                            quantity: quantity
                        })

                    });


                const data =
                    await response.json();


                if (!response.ok || !data.success) {

                    throw new Error(
                        data.message || 'Unable to update cart'
                    );

                }


                window.location.reload();


            } catch (error) {

                console.error(
                    'Cart update error:',
                    error
                );

                alert(
                    error.message ||
                    'Unable to update cart'
                );

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Remove item
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll(
        '.remove-item'
    ).forEach(function (button) {

        button.addEventListener('click', async function () {

            if (!confirm('Remove this item from cart?')) {
                return;
            }


            const lineId =
                this.getAttribute('data-line-id');


            try {

                const response =
                    await fetch('{{ route('cart.remove') }}', {

                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },

                        body: JSON.stringify({
                            line_id: lineId
                        })

                    });


                const data =
                    await response.json();


                if (!response.ok || !data.success) {

                    throw new Error(
                        data.message || 'Unable to remove item'
                    );

                }


                window.location.reload();


            } catch (error) {

                console.error(
                    'Cart remove error:',
                    error
                );

                alert(
                    error.message ||
                    'Unable to remove item'
                );

            }

        });

    });

});

</script>

@endsection