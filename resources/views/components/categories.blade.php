@props(['categories'])

<section class="categories-section py-12 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10 md:mb-12">
            <span class="text-sm text-gray-500 uppercase tracking-wider">Shop by Category</span>
            <h2 class="text-3xl md:text-4xl font-light mt-2">
                Browse Our <span class="font-bold">Collections</span>
            </h2>
            <p class="text-gray-600 mt-3 max-w-2xl mx-auto">
                Find exactly what you're looking for in our curated categories
            </p>
        </div>
        
        @if($categories && count($categories) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
                @foreach($categories as $category)
                    <a href="/collections/{{ $category['handle'] }}" 
                       class="category-card group relative overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-xl transition-all duration-500 hover:-translate-y-2">
                        
                       
                        <!-- Image -->
                        <div class="h-32 md:h-40 overflow-hidden bg-gray-100">

                            @if($category['image'] ?? false)
                                <img src="{{ $category['image'] }}" 
                                     alt="{{ $category['name'] ?? $category['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-5xl bg-gradient-to-br {{ $category['color'] ?? 'from-gray-100 to-gray-200' }}">
                                    {{ $category['icon'] ?? '📦' }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-gray-800 text-sm md:text-base capitalize">
                                    {{ $category['name'] ?? $category['title'] ?? 'Collection' }}
                                </h3>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                    {{ $category['count'] ?? $category['productCount'] ?? 0 }}
                                </span>
                            </div>
                            
                            <div class="mt-2 inline-flex items-center text-xs font-medium text-gray-600 group-hover:text-gray-900">
                                Shop Collection
                                <span class="ml-1 transform group-hover:translate-x-1 transition-transform">→</span>
                            </div>
                        </div>
                        
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl"></div>
                    </a>
                @endforeach
            </div>
            
            <div class="text-center mt-10">
                <a href="/collections" class="inline-flex items-center gap-2 text-gray-700 hover:text-gray-900 font-semibold transition group">
                    View All Collections
                    <span class="transform group-hover:translate-x-1 transition">→</span>
                </a>
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-2xl shadow-sm p-8">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-xl font-semibold text-gray-800">No Collections Found</h3>
                <p class="text-gray-600 mt-2 max-w-md mx-auto">
                    You haven't created any collections in your Shopify store yet.
                </p>
                <div class="mt-6 flex flex-wrap justify-center gap-4">
                    <a href="https://{{ config('shopify.store') }}/admin/collections" 
                       target="_blank" 
                       class="inline-block bg-gray-900 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                        Create Collection in Shopify
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
    .category-card {
        min-height: auto;
    }
    
    @media (max-width: 640px) {
        .category-card .h-32 {
            height: 100px;
        }
    }
</style>