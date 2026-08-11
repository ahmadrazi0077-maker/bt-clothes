<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ShopifyService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class ShopifyController extends Controller
{
    protected $shopify;
    
    public function __construct(ShopifyService $shopify)
    {
        $this->shopify = $shopify;
    }
    
    // ============================================
    // CATEGORIES PAGE
    // ============================================
    
    public function categories()
    {
        try {
            $query = '
                query GetCollections($first: Int!) {
                    collections(first: $first) {
                        edges {
                            node {
                                id
                                title
                                handle
                                description
                                image {
                                    url
                                    altText
                                }
                                products(first: 250) {
                                    edges {
                                        node {
                                            id
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            ';
            
            $result = $this->shopify->graphqlQuery($query, ['first' => 20]);
            
            $categories = [];
            if ($result && isset($result['collections']['edges'])) {
                foreach ($result['collections']['edges'] as $edge) {
                    $node = $edge['node'];
                    
                    // 🔥 Products count manually
                    $count = 0;
                    if (isset($node['products']['edges'])) {
                        $count = count($node['products']['edges']);
                    }
                    
                    $categories[] = [
                        'id' => $node['id'],
                        'name' => $node['title'],
                        'title' => $node['title'],
                        'handle' => $node['handle'],
                        'description' => $node['description'] ?? '',
                        'image' => $node['image']['url'] ?? null,
                        'imageAlt' => $node['image']['altText'] ?? $node['title'],
                        'count' => $count,
                        'productCount' => $count,
                        'icon' => $this->getCategoryIcon($node['title']),
                        'color' => $this->getCategoryColor($node['title'])
                    ];
                }
            }
            
            return view('shop.categories', [
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Categories error: ' . $e->getMessage());
            return view('shop.categories', ['categories' => []]);
        }
    }
    
    // ============================================
    // COLLECTIONS PAGE
    // ============================================
    
    public function collections()
    {
        try {
            $query = '
                query GetCollections($first: Int!) {
                    collections(first: $first) {
                        edges {
                            node {
                                id
                                title
                                handle
                                description
                                image {
                                    url
                                    altText
                                }
                                products(first: 250) {
                                    edges {
                                        node {
                                            id
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            ';
            
            $result = $this->shopify->graphqlQuery($query, ['first' => 30]);
            
            $collections = [];
            if ($result && isset($result['collections']['edges'])) {
                foreach ($result['collections']['edges'] as $edge) {
                    $node = $edge['node'];
                    
                    // 🔥 Products count manually
                    $count = 0;
                    if (isset($node['products']['edges'])) {
                        $count = count($node['products']['edges']);
                    }
                    
                    $collections[] = [
                        'id' => $node['id'],
                        'title' => $node['title'],
                        'handle' => $node['handle'],
                        'description' => $node['description'] ?? '',
                        'image' => $node['image']['url'] ?? null,
                        'imageAlt' => $node['image']['altText'] ?? $node['title'],
                        'productCount' => $count,
                        'products' => [],
                        'icon' => $this->getCategoryIcon($node['title']),
                        'color' => $this->getCategoryColor($node['title'])
                    ];
                }
            }
            
            return view('shop.collections', [
                'collections' => $collections
            ]);
        } catch (\Exception $e) {
            Log::error('Collections error: ' . $e->getMessage());
            return view('shop.collections', ['collections' => []]);
        }
    }
    
    // ============================================
    // HOME PAGE
    // ============================================
    
    /**
 * Homepage
 */
public function home()
{
    try {
        $products = $this->shopify->getProductsGraphQL(8);
        
        $query = '
            query GetCollections($first: Int!) {
                collections(first: $first) {
                    edges {
                        node {
                            id
                            title
                            handle
                            image {
                                url
                                altText
                            }
                            products(first: 250) {
                                edges {
                                    node {
                                        id
                                    }
                                }
                            }
                        }
                    }
                }
            }
        ';
        
        $result = $this->shopify->graphqlQuery($query, ['first' => 8]);
        
        $collections = [];
        if ($result && isset($result['collections']['edges'])) {
            foreach ($result['collections']['edges'] as $edge) {
                $node = $edge['node'];
                
                $count = 0;
                if (isset($node['products']['edges'])) {
                    $count = count($node['products']['edges']);
                }
                
                $collections[] = [
                    'id' => $node['id'],
                    'name' => $node['title'],
                    'title' => $node['title'],
                    'handle' => $node['handle'],
                    'description' => '',
                    'image' => $node['image']['url'] ?? null,
                    'imageAlt' => $node['image']['altText'] ?? $node['title'],
                    'count' => $count,
                    'productCount' => $count,
                    'icon' => $this->getCategoryIcon($node['title']),
                    'color' => $this->getCategoryColor($node['title'])
                ];
            }
        }
        
        // Newsletter Image
        $newsletterImage = 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=600&h=400&fit=crop';
        
        return view('home', [
            'featured_products' => $products,
            'categories' => $collections,
            'newsletter_image' => $newsletterImage
        ]);
    } catch (\Exception $e) {
        Log::error('Home error: ' . $e->getMessage());
        return view('home', [
            'featured_products' => [],
            'categories' => []
        ]);
    }
}
    
    // ============================================
    // SINGLE COLLECTION PAGE
    // ============================================
    
    public function collection($handle)
    {
        try {
            $query = '
                query GetCollection($handle: String!) {
                    collectionByHandle(handle: $handle) {
                        id
                        title
                        handle
                        description
                        image {
                            url
                            altText
                        }
                        products(first: 250) {
                            edges {
                                node {
                                    id
                                    title
                                    handle
                                    description
                                    availableForSale
                                    priceRange {
                                        minVariantPrice {
                                            amount
                                            currencyCode
                                        }
                                    }
                                    compareAtPriceRange {
                                        minVariantPrice {
                                            amount
                                            currencyCode
                                        }
                                    }
                                    images(first: 1) {
                                        edges {
                                            node {
                                                url
                                                altText
                                            }
                                        }
                                    }
                                    variants(first: 1) {
                                        edges {
                                            node {
                                                id
                                                title
                                                price {
                                                    amount
                                                    currencyCode
                                                }
                                                availableForSale
                                            }
                                        }
                                    }
                                    tags
                                    vendor
                                }
                            }
                        }
                    }
                }
            ';
            
            $result = $this->shopify->graphqlQuery($query, ['handle' => $handle]);
            
            if ($result && isset($result['collectionByHandle'])) {
                $node = $result['collectionByHandle'];
                $products = [];
                
                if (isset($node['products']['edges'])) {
                    foreach ($node['products']['edges'] as $productEdge) {
                        $productNode = $productEdge['node'];
                        $products[] = [
                            'id' => $productNode['id'],
                            'title' => $productNode['title'],
                            'handle' => $productNode['handle'],
                            'description' => $productNode['description'],
                            'availableForSale' => $productNode['availableForSale'],
                            'price' => $productNode['priceRange']['minVariantPrice']['amount'] ?? '0.00',
                            'comparePrice' => $productNode['compareAtPriceRange']['minVariantPrice']['amount'] ?? null,
                            'image' => $productNode['images']['edges'][0]['node']['url'] ?? null,
                            'variantId' => $productNode['variants']['edges'][0]['node']['id'] ?? null,
                            'vendor' => $productNode['vendor'] ?? '',
                            'tags' => $productNode['tags'] ?? []
                        ];
                    }
                }
                
                $collection = [
                    'id' => $node['id'],
                    'title' => $node['title'],
                    'handle' => $node['handle'],
                    'description' => $node['description'] ?? '',
                    'image' => $node['image']['url'] ?? null,
                    'imageAlt' => $node['image']['altText'] ?? $node['title'],
                    'productCount' => count($products),
                    'products' => $products,
                    'icon' => $this->getCategoryIcon($node['title']),
                    'color' => $this->getCategoryColor($node['title'])
                ];
                
                return view('shop.collection', [
                    'collection' => $collection,
                    'products' => $products
                ]);
            }
            
            abort(404);
        } catch (\Exception $e) {
            Log::error('Collection error: ' . $e->getMessage());
            abort(404);
        }
    }
    
    // ============================================
    // PRODUCTS
    // ============================================
    
    public function products(Request $request)
    {
        try {
            $products = $this->shopify->getProductsGraphQL($request->limit ?? 12);
            return view('shop.index', ['products' => $products]);
        } catch (\Exception $e) {
            Log::error('Products error: ' . $e->getMessage());
            return view('shop.index', ['products' => []]);
        }
    }
    
    public function product($handle)
    {
        try {
            $product = $this->shopify->getProductByHandle($handle);
            
            if (!$product) {
                abort(404);
            }
            
            $recommendations = $this->shopify->getProductRecommendations($product['id']);
            
            return view('shop.product', [
                'product' => $product,
                'recommendations' => $recommendations
            ]);
        } catch (\Exception $e) {
            Log::error('Product error: ' . $e->getMessage());
            abort(404);
        }
    }
    
    // ============================================
    // CART METHODS
    // ============================================
    
    protected function getCartId()
    {
        $cartId = Session::get('shopify_cart_id');
        
        if (!$cartId) {
            $cart = $this->shopify->createCart();
            if ($cart) {
                $cartId = $cart['id'];
                Session::put('shopify_cart_id', $cartId);
                Session::put('shopify_cart', $cart);
            }
        }
        
        return $cartId;
    }
    
    public function cart()
    {
        $cartId = Session::get('shopify_cart_id');
        $cart = null;
        
        if ($cartId) {
            $cart = $this->shopify->getCart($cartId);
        }
        
        // Calculate totals
        $subtotal = 0;
        $itemCount = 0;
        $cartItems = [];
        
        if ($cart && isset($cart['lines']['edges'])) {
            $cartItems = $cart['lines']['edges'];
            foreach ($cartItems as $item) {
                $node = $item['node'];
                $price = $node['merchandise']['price']['amount'] ?? 0;
                $quantity = $node['quantity'] ?? 1;
                $subtotal += $price * $quantity;
                $itemCount += $quantity;
            }
        }
        
        return view('cart.index', [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'itemCount' => $itemCount,
            'tax' => 0
        ]);
    }
    
    public function addToCart(Request $request)
    {
        try {
            $variantId = $request->variant_id;
            $quantity = $request->quantity ?? 1;
            
            if (!$variantId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Variant ID required'
                ], 400);
            }
            
            // Get or create cart
            $cartId = Session::get('shopify_cart_id');
            
            if (!$cartId) {
                $cart = $this->shopify->createCart();
                if ($cart) {
                    $cartId = $cart['id'];
                    Session::put('shopify_cart_id', $cartId);
                }
            }
            
            if (!$cartId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to create cart'
                ], 500);
            }
            
            // Add to cart
            $lineItems = [
                [
                    'quantity' => $quantity,
                    'merchandiseId' => $variantId
                ]
            ];
            
            $cart = $this->shopify->addToCart($cartId, $lineItems);
            
            if ($cart) {
                Session::put('shopify_cart', $cart);
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart!',
                    'itemCount' => $cart['totalQuantity'] ?? 0
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to add to cart'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function updateCart(Request $request)
    {
        try {
            $cartId = Session::get('shopify_cart_id');
            $lineId = $request->line_id;
            $quantity = $request->quantity;
            
            if ($quantity <= 0) {
                $cart = $this->shopify->removeCartLine($cartId, [$lineId]);
            } else {
                $cart = $this->shopify->updateCartLine($cartId, $lineId, $quantity);
            }
            
            if ($cart) {
                Session::put('shopify_cart', $cart);
                return response()->json([
                    'success' => true,
                    'cart' => $cart,
                    'itemCount' => $cart['totalQuantity'] ?? 0
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to update cart'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function removeFromCart(Request $request)
    {
        try {
            $cartId = Session::get('shopify_cart_id');
            $lineId = $request->line_id;
            
            $cart = $this->shopify->removeCartLine($cartId, [$lineId]);
            
            if ($cart) {
                Session::put('shopify_cart', $cart);
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'itemCount' => $cart['totalQuantity'] ?? 0
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to remove item'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function clearCart()
    {
        try {
            $cartId = Session::get('shopify_cart_id');
            
            if ($cartId) {
                $cart = $this->shopify->getCart($cartId);
                if ($cart && isset($cart['lines']['edges'])) {
                    $lineIds = array_map(function($edge) {
                        return $edge['node']['id'];
                    }, $cart['lines']['edges']);
                    
                    if (!empty($lineIds)) {
                        $this->shopify->removeCartLine($cartId, $lineIds);
                    }
                }
            }
            
            Session::forget('shopify_cart_id');
            Session::forget('shopify_cart');
            
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function cartCount()
    {
        try {
            $cart = Session::get('shopify_cart');
            if ($cart) {
                return response()->json(['count' => $cart['totalQuantity'] ?? 0]);
            }
            return response()->json(['count' => 0]);
        } catch (\Exception $e) {
            return response()->json(['count' => 0]);
        }
    }
    
    public function applyDiscount(Request $request)
    {
        try {
            $cartId = $this->getCartId();
            $discountCode = $request->discount_code;
            
            if (!$discountCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please enter a discount code'
                ], 400);
            }
            
            $cart = $this->shopify->addDiscount($cartId, $discountCode);
            
            if ($cart) {
                Session::put('shopify_cart', $cart);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Discount applied!',
                    'cart' => $cart,
                    'total' => $cart['estimatedCost']['totalAmount']['amount'] ?? 0
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid discount code'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Apply discount error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error applying discount'
            ], 500);
        }
    }
    
   
    
    public function checkout()
    {
        try {
            $cartId = $this->getCartId();
            
            if (!$cartId) {
                return redirect()->route('cart')->with('error', 'Your cart is empty');
            }
            
            $cart = $this->shopify->getCart($cartId);
            
            if ($cart && isset($cart['checkoutUrl'])) {
                Session::forget('shopify_cart_id');
                Session::forget('shopify_cart');
                return redirect($cart['checkoutUrl']);
            }
            
            return redirect()->route('cart')->with('error', 'Unable to process checkout');
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('cart')->with('error', 'Error during checkout');
        }
    }
    
    // ============================================
    // HELPER METHODS
    // ============================================
    
    protected function getCategoryIcon($name)
    {
        $icons = [
            'first' => '✨',
            'clothes' => '👕',
            'new' => '✨', 'arrivals' => '✨',
            'tops' => '👕', 'shirts' => '👕',
            'bottoms' => '👖', 'pants' => '👖', 'jeans' => '👖',
            'outerwear' => '🧥', 'jackets' => '🧥', 'coats' => '🧥',
            'dresses' => '👗',
            'accessories' => '👜', 'bags' => '👜',
            'sale' => '🏷️',
            'sustainable' => '🌿',
            'winter' => '❄️', 'summer' => '☀️',
            'spring' => '🌸', 'fall' => '🍂',
            'men' => '👔', 'women' => '👩', 'kids' => '🧒',
            'shoes' => '👟', 'footwear' => '👟',
            'jewelry' => '💍', 'watches' => '⌚'
        ];
        
        $nameLower = strtolower($name);
        foreach ($icons as $key => $icon) {
            if (strpos($nameLower, strtolower($key)) !== false) {
                return $icon;
            }
        }
        return '📦';
    }
    
    protected function getCategoryColor($name)
    {
        $colors = [
            'first' => 'from-purple-100 to-pink-100',
            'clothes' => 'from-blue-100 to-cyan-100',
            'new' => 'from-purple-100 to-pink-100',
            'arrivals' => 'from-purple-100 to-pink-100',
            'tops' => 'from-blue-100 to-cyan-100',
            'shirts' => 'from-blue-100 to-cyan-100',
            'bottoms' => 'from-green-100 to-emerald-100',
            'pants' => 'from-green-100 to-emerald-100',
            'jeans' => 'from-blue-200 to-indigo-200',
            'outerwear' => 'from-orange-100 to-amber-100',
            'jackets' => 'from-orange-100 to-amber-100',
            'coats' => 'from-orange-100 to-amber-100',
            'dresses' => 'from-red-100 to-rose-100',
            'accessories' => 'from-yellow-100 to-orange-100',
            'bags' => 'from-yellow-100 to-orange-100',
            'sale' => 'from-red-200 to-pink-200',
            'sustainable' => 'from-green-200 to-teal-200',
            'winter' => 'from-blue-200 to-gray-200',
            'summer' => 'from-yellow-200 to-orange-200',
            'spring' => 'from-pink-200 to-purple-200',
            'fall' => 'from-orange-200 to-brown-200',
            'men' => 'from-blue-200 to-gray-200',
            'women' => 'from-pink-200 to-purple-200',
            'kids' => 'from-green-200 to-yellow-200',
            'shoes' => 'from-gray-200 to-blue-200'
        ];
        
        $nameLower = strtolower($name);
        foreach ($colors as $key => $color) {
            if (strpos($nameLower, strtolower($key)) !== false) {
                return $color;
            }
        }
        return 'from-gray-100 to-gray-200';
    }

    /**
 * Search Products
 */
public function search(Request $request)
{
    try {
        $query = $request->q;
        $products = [];
        
        if ($query) {
            // Shopify GraphQL Search Query
            $searchQuery = '
                query SearchProducts($query: String!, $first: Int!) {
                    products(first: $first, query: $query) {
                        edges {
                            node {
                                id
                                title
                                handle
                                description
                                availableForSale
                                priceRange {
                                    minVariantPrice {
                                        amount
                                        currencyCode
                                    }
                                }
                                compareAtPriceRange {
                                    minVariantPrice {
                                        amount
                                        currencyCode
                                    }
                                }
                                images(first: 1) {
                                    edges {
                                        node {
                                            url
                                            altText
                                            width
                                            height
                                        }
                                    }
                                }
                                variants(first: 1) {
                                    edges {
                                        node {
                                            id
                                            title
                                            price {
                                                amount
                                                currencyCode
                                            }
                                            availableForSale
                                        }
                                    }
                                }
                                tags
                                vendor
                            }
                        }
                        pageInfo {
                            hasNextPage
                            endCursor
                        }
                    }
                }
            ';
            
            $variables = [
                'query' => $query,
                'first' => 20
            ];
            
            $result = $this->shopify->graphqlQuery($searchQuery, $variables);
            
            if ($result && isset($result['products']['edges'])) {
                $products = array_map(function($edge) {
                    return $edge['node'];
                }, $result['products']['edges']);
            }
        }
        
        return view('search', [
            'products' => $products,
            'query' => $query,
            'total' => count($products)
        ]);
    } catch (\Exception $e) {
        Log::error('Search error: ' . $e->getMessage());
        return view('search', [
            'products' => [],
            'query' => $request->q ?? '',
            'total' => 0
        ]);
    }
}


}