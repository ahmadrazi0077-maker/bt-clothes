<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ShopifyService
{
    protected $store;
    protected $accessToken;
    protected $storefrontToken;
    protected $apiVersion;
    
    public function __construct()
    {
        $this->store = config('shopify.store');
        $this->accessToken = config('shopify.access_token');
        $this->storefrontToken = config('shopify.storefront_token');
        $this->apiVersion = config('shopify.api_version', '2024-07');
        
        if (empty($this->store)) {
            Log::error('Shopify store URL is missing in .env');
        }
        if (empty($this->storefrontToken)) {
            Log::error('Shopify storefront token is missing in .env');
        }
    }
    
    protected function getStorefrontHeaders()
    {
        return [
            'X-Shopify-Storefront-Access-Token' => $this->storefrontToken,
            'Content-Type' => 'application/json',
        ];
    }
    
    public function graphqlQuery($query, $variables = [])
    {
        if (empty($this->store) || empty($this->storefrontToken)) {
            return null;
        }
        
        $url = "https://{$this->store}/api/{$this->apiVersion}/graphql.json";
        
        try {
            $response = Http::withHeaders($this->getStorefrontHeaders())
                ->timeout(30)
                ->post($url, [
                    'query' => $query,
                    'variables' => $variables
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['errors'])) {
                    Log::error('GraphQL Errors:', $data['errors']);
                    return null;
                }
                return $data['data'] ?? null;
            }
            
            Log::error('GraphQL API Error: ' . $response->status() . ' - ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('GraphQL Exception: ' . $e->getMessage());
            return null;
        }
    }
    
    // ============================================
    // PRODUCT METHODS
    // ============================================
    
   public function getProductsGraphQL($limit = 12)
{
    $query = '
    query GetProducts($first: Int!) {
        products(first: $first) {
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

                    images(first: 2) {
                        edges {
                            node {
                                url
                                altText
                                width
                                height
                            }
                        }
                    }

                    variants(first: 100) {
                        edges {
                            node {
                                id
                                title
                                price {
                                    amount
                                    currencyCode
                                }
                                availableForSale

                                selectedOptions {
                                    name
                                    value
                                }
                            }
                        }
                    }

                    tags
                    vendor
                }
            }
        }
    }
    ';

    $result = $this->graphqlQuery(
        $query,
        ['first' => $limit]
    );

    if (
        $result &&
        isset($result['products']['edges'])
    ) {
        return array_map(
            function ($edge) {
                return $edge['node'];
            },
            $result['products']['edges']
        );
    }

    return $this->getMockProducts($limit);
}
    public function getProductByHandle($handle)
    {
        $query = '
            query GetProduct($handle: String!) {
                productByHandle(handle: $handle) {
                    id
                    title
                    handle
                    description
                    descriptionHtml
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
                    images(first: 5) {
                        edges {
                            node {
                                url
                                altText
                            }
                        }
                    }
                    variants(first: 10) {
                        edges {
                            node {
                                id
                                title
                                price {
                                    amount
                                    currencyCode
                                }
                                availableForSale
                                selectedOptions {
                                    name
                                    value
                                }
                            }
                        }
                    }
                    options {
                        name
                        values
                    }
                    tags
                    vendor
                }
            }
        ';
        
        $result = $this->graphqlQuery($query, ['handle' => $handle]);
        
        if ($result && isset($result['productByHandle'])) {
            return $result['productByHandle'];
        }
        
        return null;
    }
    
   /**
 * Get Product by ID
 */
public function getProductById($id)
{
    // Clean the ID if it has the full URI format
    $cleanId = str_replace('gid://shopify/Product/', '', $id);
    $cleanId = str_replace('gid://shopify/ProductVariant/', '', $cleanId);
    
    $query = '
        query GetProductById($id: ID!) {
            node(id: $id) {
                ... on Product {
                    id
                    title
                    handle
                    description
                    descriptionHtml
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
                    images(first: 5) {
                        edges {
                            node {
                                url
                                altText
                                width
                                height
                            }
                        }
                    }
                    variants(first: 10) {
                        edges {
                            node {
                                id
                                title
                                price {
                                    amount
                                    currencyCode
                                }
                                availableForSale
                                selectedOptions {
                                    name
                                    value
                                }
                            }
                        }
                    }
                    options {
                        name
                        values
                    }
                    tags
                    vendor
                }
            }
        }
    ';
    
    // Try with original ID first
    $result = $this->graphqlQuery($query, ['id' => $id]);
    
    // If not found, try with clean ID
    if (!$result || !isset($result['node'])) {
        $result = $this->graphqlQuery($query, ['id' => $cleanId]);
    }
    
    if ($result && isset($result['node'])) {
        return $result['node'];
    }
    
    return null;
}

/**
 * Get Product by Handle
 */

    
    public function getProductRecommendations($productId)
    {
        $query = '
            query GetRecommendations($productId: ID!) {
                productRecommendations(productId: $productId) {
                    id
                    title
                    handle
                    priceRange {
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
                }
            }
        ';
        
        $result = $this->graphqlQuery($query, ['productId' => $productId]);
        
        if ($result && isset($result['productRecommendations'])) {
            return $result['productRecommendations'];
        }
        
        return $this->getMockProducts(4);
    }
    
    // ============================================
    // COLLECTION METHODS
    // ============================================
    
    public function getCollectionsWithCount($limit = 10)
    {
        Cache::forget('shopify_collections');
        
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
        
        $result = $this->graphqlQuery($query, ['first' => $limit]);
        
        if ($result && isset($result['collections']['edges'])) {
            $collections = [];
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
            
            Cache::put('shopify_collections', $collections, 3600);
            return $collections;
        }
        
        return [];
    }
    
    // ============================================
    // CART METHODS
    // ============================================
    
  public function createCart($lineItems = [])
{
    $query = '
        mutation CartCreate($input: CartInput!) {
            cartCreate(input: $input) {
                cart {
                    id
                    checkoutUrl
                    totalQuantity
                }
            }
        }
    ';
    
    $variables = [
        'input' => [
            'lines' => $lineItems
        ]
    ];
    
    $result = $this->graphqlQuery($query, $variables);
    
    if ($result && isset($result['cartCreate']['cart'])) {
        return $result['cartCreate']['cart'];
    }
    
    return null;
}

public function getCart($cartId)
{
    $query = '
        query GetCart($cartId: ID!) {
            cart(id: $cartId) {
                id
                checkoutUrl
                totalQuantity
                lines(first: 20) {
                    edges {
                        node {
                            id
                            quantity
                            merchandise {
                                ... on ProductVariant {
                                    id
                                    title
                                    price {
                                        amount
                                        currencyCode
                                    }
                                    product {
                                        id
                                        title
                                        handle
                                        images(first: 1) {
                                            edges {
                                                node {
                                                    url
                                                    altText
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                estimatedCost {
                    subtotalAmount {
                        amount
                        currencyCode
                    }
                    totalAmount {
                        amount
                        currencyCode
                    }
                }
            }
        }
    ';
    
    $result = $this->graphqlQuery($query, ['cartId' => $cartId]);
    
    if ($result && isset($result['cart'])) {
        return $result['cart'];
    }
    
    return null;
}

public function addToCart($cartId, $lineItems)
{
    
    $query = '
        mutation CartLinesAdd($cartId: ID!, $lines: [CartLineInput!]!) {
            cartLinesAdd(cartId: $cartId, lines: $lines) {
                cart {
                    id
                    totalQuantity
                }
            }
        }
    ';
    
    $variables = [
        'cartId' => $cartId,
        'lines' => $lineItems
    ];
    
    $result = $this->graphqlQuery($query, $variables);
    
    if ($result && isset($result['cartLinesAdd']['cart'])) {
        return $result['cartLinesAdd']['cart'];
    }
    
    return null;
}

public function updateCartLine($cartId, $lineId, $quantity)
{
    $query = '
        mutation CartLinesUpdate($cartId: ID!, $lines: [CartLineUpdateInput!]!) {
            cartLinesUpdate(cartId: $cartId, lines: $lines) {
                cart {
                    id
                    totalQuantity
                }
            }
        }
    ';
    
    $variables = [
        'cartId' => $cartId,
        'lines' => [
            [
                'id' => $lineId,
                'quantity' => $quantity
            ]
        ]
    ];
    
    $result = $this->graphqlQuery($query, $variables);
    
    if ($result && isset($result['cartLinesUpdate']['cart'])) {
        return $result['cartLinesUpdate']['cart'];
    }
    
    return null;
}

public function removeCartLine($cartId, $lineIds)
{
    $query = '
        mutation CartLinesRemove($cartId: ID!, $lineIds: [ID!]!) {
            cartLinesRemove(cartId: $cartId, lineIds: $lineIds) {
                cart {
                    id
                    totalQuantity
                }
            }
        }
    ';
    
    $variables = [
        'cartId' => $cartId,
        'lineIds' => $lineIds
    ];
    
    $result = $this->graphqlQuery($query, $variables);
    
    if ($result && isset($result['cartLinesRemove']['cart'])) {
        return $result['cartLinesRemove']['cart'];
    }
    
    return null;
}
    
    public function addDiscount($cartId, $discountCode)
    {
        $query = '
            mutation CartDiscountCodesUpdate($cartId: ID!, $discountCodes: [String!]) {
                cartDiscountCodesUpdate(cartId: $cartId, discountCodes: $discountCodes) {
                    cart {
                        id
                        discountCodes {
                            code
                            applicable
                        }
                        estimatedCost {
                            subtotalAmount {
                                amount
                                currencyCode
                            }
                            totalAmount {
                                amount
                                currencyCode
                            }
                        }
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }
        ';
        
        $variables = [
            'cartId' => $cartId,
            'discountCodes' => [$discountCode]
        ];
        
        $result = $this->graphqlQuery($query, $variables);
        
        if ($result && isset($result['cartDiscountCodesUpdate']['cart'])) {
            return $result['cartDiscountCodesUpdate']['cart'];
        }
        
        return null;
    }
    
    // ============================================
    // MOCK DATA
    // ============================================
    
    protected function getMockProducts($limit = 12)
    {
        $products = [];
        for ($i = 1; $i <= $limit; $i++) {
            $products[] = [
                'id' => "mock_{$i}",
                'title' => "Mock Product {$i}",
                'handle' => "mock-product-{$i}",
                'description' => "This is mock product {$i} for testing.",
                'availableForSale' => true,
                'vendor' => 'BT Clothes',
                'priceRange' => [
                    'minVariantPrice' => [
                        'amount' => number_format(rand(20, 100), 2),
                        'currencyCode' => 'USD'
                    ]
                ],
                'compareAtPriceRange' => rand(0, 1) ? [
                    'minVariantPrice' => [
                        'amount' => number_format(rand(50, 150), 2),
                        'currencyCode' => 'USD'
                    ]
                ] : null,
                'images' => ['edges' => []],
                'variants' => [
                    'edges' => [
                        [
                            'node' => [
                                'id' => "variant_{$i}",
                                'title' => 'Default Title',
                                'price' => [
                                    'amount' => number_format(rand(20, 100), 2),
                                    'currencyCode' => 'USD'
                                ],
                                'availableForSale' => true
                            ]
                        ]
                    ]
                ]
            ];
        }
        return $products;
    }
    
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

    public function product($handle)
{
    try {
        $product = $this->shopify->getProductByHandle($handle);
        
        if (!$product) {
            abort(404);
        }
        
        // ✅ Get recommendations based on collection/tags/vendor
        $recommendations = $this->shopify->getRecommendationsByProduct($product);
        
        return view('shop.product', [
            'product' => $product,
            'recommendations' => $recommendations
        ]);
    } catch (\Exception $e) {
        Log::error('Product error: ' . $e->getMessage());
        abort(404);
    }
}

/**
 * Get Recommendations By Product
 * - Pehle same collection se products
 * - Agar collection nahi toh same tags/vendor se
 */
public function getRecommendationsByProduct($product, $limit = 4)
{
    try {
        // Step 1: Get product's collection handles
        $collectionHandles = $this->getProductCollectionHandles($product['handle']);
        
        $recommendations = [];
        
        // Step 2: Try to get products from same collections
        if (!empty($collectionHandles)) {
            foreach ($collectionHandles as $collectionHandle) {
                $collectionProducts = $this->getCollectionProductsRecommendations($collectionHandle, $limit);
                foreach ($collectionProducts as $p) {
                    if ($p['handle'] !== $product['handle'] && !in_array($p['handle'], array_column($recommendations, 'handle'))) {
                        $recommendations[] = $p;
                    }
                }
                if (count($recommendations) >= $limit) {
                    break;
                }
            }
        }
        
        // Step 3: If not enough, get from same tags
        if (count($recommendations) < $limit) {
            $tagProducts = $this->getProductsByTags($product['tags'] ?? [], $limit, $product['handle']);
            foreach ($tagProducts as $p) {
                if (!in_array($p['handle'], array_column($recommendations, 'handle'))) {
                    $recommendations[] = $p;
                }
            }
        }
        
        // Step 4: If still not enough, get from same vendor
        if (count($recommendations) < $limit) {
            $vendorProducts = $this->getProductsByVendor($product['vendor'] ?? '', $limit, $product['handle']);
            foreach ($vendorProducts as $p) {
                if (!in_array($p['handle'], array_column($recommendations, 'handle'))) {
                    $recommendations[] = $p;
                }
            }
        }
        
        // Step 5: If still not enough, get any random products
        if (count($recommendations) < $limit) {
            $randomProducts = $this->getRandomProducts($limit, $product['handle']);
            foreach ($randomProducts as $p) {
                if (!in_array($p['handle'], array_column($recommendations, 'handle'))) {
                    $recommendations[] = $p;
                }
            }
        }
        
        return array_slice($recommendations, 0, $limit);
        
    } catch (\Exception $e) {
        Log::error('Recommendations error: ' . $e->getMessage());
        return $this->getRandomProducts($limit, $product['handle'] ?? '');
    }
}

/**
 * Get Product Collection Handles
 */
protected function getProductCollectionHandles($productHandle)
{
    $query = '
        query GetProductCollections($handle: String!) {
            productByHandle(handle: $handle) {
                collections(first: 5) {
                    edges {
                        node {
                            handle
                        }
                    }
                }
            }
        }
    ';
    
    $result = $this->graphqlQuery($query, ['handle' => $productHandle]);
    
    $collections = [];
    if ($result && isset($result['productByHandle']['collections']['edges'])) {
        foreach ($result['productByHandle']['collections']['edges'] as $edge) {
            $collections[] = $edge['node']['handle'];
        }
    }
    
    return $collections;
}

/**
 * Get Collection Products for Recommendations
 */
protected function getCollectionProductsRecommendations($collectionHandle, $limit)
{
    $query = '
        query GetCollectionProducts($handle: String!, $first: Int!) {
            collectionByHandle(handle: $handle) {
                products(first: $first) {
                    edges {
                        node {
                            id
                            title
                            handle
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
                            vendor
                            tags
                        }
                    }
                }
            }
        }
    ';
    
    $result = $this->graphqlQuery($query, [
        'handle' => $collectionHandle,
        'first' => $limit + 2
    ]);
    
    $products = [];
    if ($result && isset($result['collectionByHandle']['products']['edges'])) {
        foreach ($result['collectionByHandle']['products']['edges'] as $edge) {
            $products[] = $edge['node'];
        }
    }
    
    return $products;
}

/**
 * Get Products by Tags
 */
protected function getProductsByTags($tags, $limit, $excludeHandle)
{
    if (empty($tags)) {
        return [];
    }
    
    $query = '
        query GetProductsByTags($first: Int!) {
            products(first: $first, query: "' . $tags[0] . '") {
                edges {
                    node {
                        id
                        title
                        handle
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
                        vendor
                        tags
                    }
                }
            }
        }
    ';
    
    $result = $this->graphqlQuery($query, ['first' => $limit + 4]);
    
    $products = [];
    if ($result && isset($result['products']['edges'])) {
        foreach ($result['products']['edges'] as $edge) {
            if ($edge['node']['handle'] !== $excludeHandle) {
                $products[] = $edge['node'];
            }
        }
    }
    
    return $products;
}

/**
 * Get Products by Vendor
 */
protected function getProductsByVendor($vendor, $limit, $excludeHandle)
{
    if (empty($vendor)) {
        return [];
    }
    
    $query = '
        query GetProductsByVendor($first: Int!) {
            products(first: $first, query: "vendor:' . $vendor . '") {
                edges {
                    node {
                        id
                        title
                        handle
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
                        vendor
                        tags
                    }
                }
            }
        }
    ';
    
    $result = $this->graphqlQuery($query, ['first' => $limit + 4]);
    
    $products = [];
    if ($result && isset($result['products']['edges'])) {
        foreach ($result['products']['edges'] as $edge) {
            if ($edge['node']['handle'] !== $excludeHandle) {
                $products[] = $edge['node'];
            }
        }
    }
    
    return $products;
}

/**
 * Get Random Products
 */
protected function getRandomProducts($limit, $excludeHandle)
{
    $query = '
        query GetRandomProducts($first: Int!) {
            products(first: $first, sortKey: CREATED_AT, reverse: true) {
                edges {
                    node {
                        id
                        title
                        handle
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
                        vendor
                        tags
                    }
                }
            }
        }
    ';
    
    $result = $this->graphqlQuery($query, ['first' => $limit + 4]);
    
    $products = [];
    if ($result && isset($result['products']['edges'])) {
        foreach ($result['products']['edges'] as $edge) {
            if ($edge['node']['handle'] !== $excludeHandle) {
                $products[] = $edge['node'];
            }
        }
    }
    
    return $products;
}
}