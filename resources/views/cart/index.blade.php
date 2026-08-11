@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-12">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-light">Shopping Cart</h1>

        @if(isset($itemCount) && $itemCount > 0)
            <span class="text-sm text-gray-500">
                {{ $itemCount }} {{ $itemCount == 1 ? 'item' : 'items' }}
            </span>
        @endif
    </div>

    @if(isset($cartItems) && count($cartItems) > 0)

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- CART ITEMS --}}
            <div class="lg:col-span-2">

                <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">

                    @foreach($cartItems as $item)

                        @php
                            $node = $item['node'] ?? [];

                            $lineId = $node['id'] ?? null;
                            $merchandise = $node['merchandise'] ?? [];

                            $product = $merchandise['product'] ?? null;

                            $image = $product['images']['edges'][0]['node']['url']
                                ?? null;

                            $imageAlt = $product['images']['edges'][0]['node']['altText']
                                ?? ($product['title'] ?? 'Product');

                            $price = (float) (
                                $merchandise['price']['amount'] ?? 0
                            );

                            $currency = $merchandise['price']['currencyCode']
                                ?? 'PKR';

                            $quantity = (int) (
                                $node['quantity'] ?? 1
                            );

                            $lineTotal = $price * $quantity;

                            $productTitle = $product['title']
                                ?? 'Product';

                            $productHandle = $product['handle']
                                ?? null;
                        @endphp

                        <div
                            class="cart-item flex flex-col sm:flex-row sm:items-center gap-4 p-5 border-b border-gray-200 last:border-b-0"
                            data-line-id="{{ $lineId }}"
                        >

                            {{-- PRODUCT IMAGE --}}
                            <div class="w-24 h-24 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">

                                @if($image)

                                    <img
                                        src="{{ $image }}"
                                        alt="{{ $imageAlt }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >

                                @else

                                    <div class="w-full h-full flex items-center justify-center text-3xl text-gray-400">
                                        👕
                                    </div>

                                @endif

                            </div>

                            {{-- PRODUCT INFO --}}
                            <div class="flex-1">

                                @if($productHandle)

                                    <a
                                        href="{{ route('product.show', ['handle' => $productHandle]) }}"
                                        class="font-semibold text-gray-900 hover:text-gray-600 transition"
                                    >
                                        {{ $productTitle }}
                                    </a>

                                @else

                                    <h3 class="font-semibold text-gray-900">
                                        {{ $productTitle }}
                                    </h3>

                                @endif

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $currency }} {{ number_format($price, 0) }}
                                </p>

                                <button
                                    type="button"
                                    class="remove-item text-sm text-red-500 hover:text-red-700 mt-2 transition"
                                    data-line-id="{{ $lineId }}"
                                >
                                    Remove
                                </button>

                            </div>

                            {{-- QUANTITY --}}
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden w-fit">

                                <button
                                    type="button"
                                    class="qty-decrease px-3 py-2 bg-gray-50 hover:bg-gray-100 transition"
                                    data-line-id="{{ $lineId }}"
                                    aria-label="Decrease quantity"
                                >
                                    −
                                </button>

                                <input
                                    type="text"
                                    class="qty-input w-12 text-center border-0 focus:ring-0"
                                    value="{{ $quantity }}"
                                    readonly
                                    data-line-id="{{ $lineId }}"
                                >

                                <button
                                    type="button"
                                    class="qty-increase px-3 py-2 bg-gray-50 hover:bg-gray-100 transition"
                                    data-line-id="{{ $lineId }}"
                                    aria-label="Increase quantity"
                                >
                                    +
                                </button>

                            </div>

                            {{-- LINE TOTAL --}}
                            <div class="text-right min-w-[100px]">

                                <div class="font-bold text-gray-900">
                                    {{ $currency }}
                                    {{ number_format($lineTotal, 0) }}
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- CONTINUE SHOPPING --}}
                <div class="mt-6">

                    <a
                        href="{{ route('products') }}"
                        class="text-sm text-gray-600 hover:text-gray-900 transition"
                    >
                        ← Continue Shopping
                    </a>

                </div>

            </div>


            {{-- CART SUMMARY --}}
            <div class="lg:col-span-1">

                <div class="border border-gray-200 rounded-xl p-6 bg-white sticky top-24">

                    <h2 class="text-xl font-semibold mb-6">
                        Order Summary
                    </h2>

                    <div class="space-y-4">

                        <div class="flex justify-between text-gray-600">
                            <span>Items</span>
                            <span>{{ $itemCount ?? 0 }}</span>
                        </div>

                        <div class="border-t border-gray-200 pt-4">

                            <div class="flex justify-between items-center">

                                <span class="text-lg font-semibold">
                                    Total
                                </span>

                                <span class="text-xl font-bold">
                                    Rs.
                                    {{ number_format((float)($subtotal ?? 0), 0) }}
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- CHECKOUT --}}
                    <a
                        href="{{ route('checkout') }}"
                        class="mt-6 w-full inline-flex justify-center items-center bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition"
                    >
                        Proceed to Checkout →
                    </a>

                </div>

            </div>

        </div>

    @else

        {{-- EMPTY CART --}}
        <div class="text-center py-20">

            <div class="text-7xl mb-6">
                🛒
            </div>

            <h2 class="text-2xl font-light text-gray-900">
                Your cart is empty
            </h2>

            <p class="text-gray-500 mt-2">
                Looks like you haven't added anything to your cart yet.
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


{{-- CART JAVASCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    /*
    |--------------------------------------------------------------------------
    | Helper: Send Cart Request
    |--------------------------------------------------------------------------
    */

    async function sendCartRequest(url, data) {

        const response = await fetch(url, {
            method: 'POST',

            headers: {
                'Content-Type': 'application/json',

                'Accept': 'application/json',

                'X-CSRF-TOKEN': csrfToken,

                'X-Requested-With': 'XMLHttpRequest'
            },

            credentials: 'same-origin',

            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(
                'Request failed with status: ' + response.status
            );
        }

        return await response.json();
    }


    /*
    |--------------------------------------------------------------------------
    | Quantity Increase / Decrease
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.qty-decrease, .qty-increase')
        .forEach(function (button) {

            button.addEventListener('click', async function () {

                const row = this.closest('.cart-item');

                if (!row) return;

                const input =
                    row.querySelector('.qty-input');

                const lineId =
                    this.dataset.lineId;

                let quantity =
                    parseInt(input.value, 10) || 1;


                /*
                | Decrease
                */

                if (
                    this.classList.contains('qty-decrease')
                ) {

                    quantity--;

                }


                /*
                | Increase
                */

                if (
                    this.classList.contains('qty-increase')
                ) {

                    quantity++;

                }


                /*
                | Minimum quantity
                */

                if (quantity < 1) {
                    quantity = 1;
                }


                /*
                | Maximum quantity
                */

                if (quantity > 10) {
                    quantity = 10;
                }


                input.value = quantity;


                /*
                | Disable buttons while request is running
                */

                const buttons =
                    row.querySelectorAll('button');

                buttons.forEach(btn => {
                    btn.disabled = true;
                });


                try {

                    const data =
                        await sendCartRequest(
                            '{{ route("cart.update") }}',
                            {
                                line_id: lineId,
                                quantity: quantity
                            }
                        );


                    if (data.success) {

                        location.reload();

                    } else {

                        alert(
                            data.message ||
                            'Unable to update cart.'
                        );

                        location.reload();

                    }

                } catch (error) {

                    console.error(
                        'Cart update error:',
                        error
                    );

                    alert(
                        'Something went wrong while updating your cart.'
                    );

                    location.reload();

                }

            });

        });


    /*
    |--------------------------------------------------------------------------
    | Remove Item
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.remove-item')
        .forEach(function (button) {

            button.addEventListener('click', async function () {

                const lineId =
                    this.dataset.lineId;

                if (!lineId) return;


                const confirmed =
                    confirm('Remove this item from cart?');

                if (!confirmed) {
                    return;
                }


                this.disabled = true;

                this.textContent = 'Removing...';


                try {

                    const data =
                        await sendCartRequest(
                            '{{ route("cart.remove") }}',
                            {
                                line_id: lineId
                            }
                        );


                    if (data.success) {

                        location.reload();

                    } else {

                        alert(
                            data.message ||
                            'Unable to remove item.'
                        );

                        this.disabled = false;

                        this.textContent = 'Remove';

                    }

                } catch (error) {

                    console.error(
                        'Cart remove error:',
                        error
                    );

                    alert(
                        'Something went wrong while removing the item.'
                    );

                    this.disabled = false;

                    this.textContent = 'Remove';

                }

            });

        });

});
</script>

@endsection