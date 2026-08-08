@props([
    'title',
    'price',
    'image',
])

<div class="group bg-white rounded-2xl overflow-hidden">

    <div class="relative overflow-hidden">

        <img
            src="{{ asset($image) }}"
            alt="{{ $title }}"
            class="w-full h-[430px] object-cover duration-500 group-hover:scale-110"
        >

        <button
            class="absolute top-4 right-4 bg-white rounded-full p-3 shadow-lg hover:bg-pink-600 hover:text-white transition"
        >
            ♡
        </button>

    </div>

    <div class="pt-5">

        <h3 class="font-semibold text-lg">
            {{ $title }}
        </h3>

        <p class="text-pink-600 text-xl font-bold mt-2">
            {{ $price }}
        </p>

    </div>

</div>