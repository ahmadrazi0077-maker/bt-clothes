<section class="bg-[#f8f5f0] py-20">

    <div class="max-w-4xl mx-auto px-6 text-center">

        <span class="text-xs uppercase tracking-[4px] text-gray-500">
            Stay in the Loop
        </span>

        <h2 class="mt-4 text-4xl md:text-5xl font-semibold text-gray-900">
            Join Our Little Bloom Family
        </h2>

        <p class="mt-5 max-w-xl mx-auto text-gray-600 leading-7">
            Subscribe to receive new collection updates, special offers
            and exclusive news.
        </p>


        {{-- Success Message --}}
        @if(session('newsletter_success'))

            <div class="mt-6 text-sm text-green-700">
                {{ session('newsletter_success') }}
            </div>

        @endif


        {{-- Error Message --}}
        @if(session('newsletter_error'))

            <div class="mt-6 text-sm text-red-600">
                {{ session('newsletter_error') }}
            </div>

        @endif


        <form
            action="{{ route('newsletter.subscribe') }}"
            method="POST"
            class="mt-8 max-w-xl mx-auto"
        >

            @csrf

            <div class="flex flex-col sm:flex-row gap-3">

                <div class="flex-1">

                    <label
                        for="newsletter-email"
                        class="sr-only"
                    >
                        Email address
                    </label>

                    <input
                        id="newsletter-email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email address"
                        required
                        autocomplete="email"
                        class="w-full rounded-full border border-gray-300 bg-white px-6 py-4 outline-none focus:border-black transition"
                    >

                    @error('email')
                        <p class="mt-2 text-left text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                <button
                    type="submit"
                    class="rounded-full bg-black px-8 py-4 text-white font-medium hover:bg-gray-800 transition"
                >
                    Subscribe
                </button>

            </div>

        </form>


        <p class="mt-5 text-xs text-gray-500">
            By subscribing, you agree to receive marketing emails from
            Little Bloom. You can unsubscribe at any time.
        </p>

    </div>

</section>