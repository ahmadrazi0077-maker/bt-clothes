@props(['products' => []])

@if(!empty($products) && count($products) > 0)
<section class="py-16 md:py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-sm text-gray-500 uppercase tracking-wider">You May Also Like</span>
            <h2 class="text-3xl md:text-4xl font-light mt-2">
                Complete <span class="font-bold">Your Look</span>
            </h2>
            <p class="text-gray-600 mt-3">Handpicked pieces to complement your style.</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                    <a href="/product/{{ $product['handle'] }}" class="block">
                        <div class="relative overflow-hidden bg-gray-100">
                            @if(isset($product['images']['edges'][0]['node']['url']))
                                <img src="{{ $product['images']['edges'][0]['node']['url'] }}" 
                                     alt="{{ $product['title'] }}"
                                     class="w-full aspect-[4/5] object-cover transition-transform duration-600 group-hover:scale-105"
                                     loading="lazy">
                            @else
                                <div class="w-full aspect-[4/5] flex items-center justify-center text-4xl text-gray-400">👕</div>
                            @endif
                            
                            @php
                                $price = $product['priceRange']['minVariantPrice']['amount'] ?? '0.00';
                                $comparePrice = $product['compareAtPriceRange']['minVariantPrice']['amount'] ?? null;
                            @endphp
                            
                            @if($comparePrice && $comparePrice > $price)
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">SALE</span>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            @if(isset($product['vendor']))
                                <div class="text-xs text-gray-500 uppercase tracking-wider">{{ $product['vendor'] }}</div>
                            @endif
                            
                            <h3 class="font-semibold mt-1 text-sm line-clamp-2">
                                {{ $product['title'] }}
                            </h3>
                            
                            <div class="flex items-center gap-2 mt-2">
                                <span class="font-bold text-lg">Rs. {{ number_format((float)$price, 0) }}</span>
                                @if($comparePrice && $comparePrice > $price)
                                    <span class="text-gray-400 line-through text-sm">Rs. {{ number_format((float)$comparePrice, 0) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif