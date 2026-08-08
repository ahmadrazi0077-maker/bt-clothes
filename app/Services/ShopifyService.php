<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
            
            Log::error('GraphQL API Error: ' . $response->status());
            return null;
        } catch (\Exception $e) {
            Log::error('GraphQL Exception: ' . $e->getMessage());
            return null;
        }
    }
    
    // ============================================
    // ✅ CART METHODS - ADD THESE
    // ============================================
    
    /**
     * Create a new cart
     */
    public function createCart($lineItems = [])
    {
        $query = '
            mutation CartCreate($input: CartInput!) {
                cartCreate(input: $input) {
                    cart {
                        id
                        checkoutUrl
                        createdAt
                        updatedAt
                        lines(first: 10) {
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
                            totalTaxAmount {
                                amount
                                currencyCode
                            }
                        }
                        discountCodes {
                            code
                            applicable
                        }
                        totalQuantity
                    }
                    userErrors {
                        field
                        message
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
    
    /**
     * Get cart by ID
     */
    public function getCart($cartId)
    {
        $query = '
            query GetCart($cartId: ID!) {
                cart(id: $cartId) {
                    id
                    checkoutUrl
                    createdAt
                    updatedAt
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
                                            vendor
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
                                attributes {
                                    key
                                    value
                                }
                                cost {
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
                        totalTaxAmount {
                            amount
                            currencyCode
                        }
                    }
                    discountCodes {
                        code
                        applicable
                    }
                    totalQuantity
                }
            }
        ';
        
        $result = $this->graphqlQuery($query, ['cartId' => $cartId]);
        
        if ($result && isset($result['cart'])) {
            return $result['cart'];
        }
        
        return null;
    }
    
    /**
     * Add items to cart
     */
    public function addToCart($cartId, $lineItems)
    {
        $query = '
            mutation CartLinesAdd($cartId: ID!, $lines: [CartLineInput!]!) {
                cartLinesAdd(cartId: $cartId, lines: $lines) {
                    cart {
                        id
                        checkoutUrl
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
                        totalQuantity
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
            'lines' => $lineItems
        ];
        
        $result = $this->graphqlQuery($query, $variables);
        
        if ($result && isset($result['cartLinesAdd']['cart'])) {
            return $result['cartLinesAdd']['cart'];
        }
        
        return null;
    }
    
    /**
     * Update cart line quantity
     */
    public function updateCartLine($cartId, $lineId, $quantity)
    {
        $query = '
            mutation CartLinesUpdate($cartId: ID!, $lines: [CartLineUpdateInput!]!) {
                cartLinesUpdate(cartId: $cartId, lines: $lines) {
                    cart {
                        id
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
                        totalQuantity
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
    
    /**
     * Remove line from cart
     */
    public function removeCartLine($cartId, $lineIds)
    {
        $query = '
            mutation CartLinesRemove($cartId: ID!, $lineIds: [ID!]!) {
                cartLinesRemove(cartId: $cartId, lineIds: $lineIds) {
                    cart {
                        id
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
                        totalQuantity
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
            'lineIds' => $lineIds
        ];
        
        $result = $this->graphqlQuery($query, $variables);
        
        if ($result && isset($result['cartLinesRemove']['cart'])) {
            return $result['cartLinesRemove']['cart'];
        }
        
        return null;
    }
    
    /**
     * Apply discount code
     */
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
        ';
        
        $result = $this->graphqlQuery($query, ['first' => $limit]);
        
        if ($result && isset($result['products']['edges'])) {
            return array_map(function($edge) {
                return $edge['node'];
            }, $result['products']['edges']);
        }
        
        return [];
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
    
    public function getProductById($id)
    {
        $query = '
            query GetProductById($id: ID!) {
                node(id: $id) {
                    ... on Product {
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
                    }
                }
            }
        ';
        
        $result = $this->graphqlQuery($query, ['id' => $id]);
        
        if ($result && isset($result['node'])) {
            return $result['node'];
        }
        
        return null;
    }
    
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
        
        return [];
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
    
    public function getAllCollections($limit = 30)
    {
        Cache::forget('shopify_all_collections');
        
        $query = '
            query GetAllCollections($first: Int!) {
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
            
            Cache::put('shopify_all_collections', $collections, 3600);
            Cache::put('shopify_collections', $collections, 3600);
            
            return $collections;
        }
        
        return [];
    }
    
    public function getCollectionByHandle($handle)
    {
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
        
        $result = $this->graphqlQuery($query, ['handle' => $handle]);
        
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
            
            return [
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
        }
        
        return null;
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
 * Search Products (Alias for controller)
 */
public function searchProducts($query, $limit = 20)
{
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
            }
        }
    ';
    
    $result = $this->graphqlQuery($searchQuery, [
        'query' => $query,
        'first' => $limit
    ]);
    
    if ($result && isset($result['products']['edges'])) {
        return array_map(function($edge) {
            return $edge['node'];
        }, $result['products']['edges']);
    }
    
    return [];
}


// ============================================
// CUSTOMER ACCOUNT METHODS
// ============================================

/**
 * Create Customer Account
 */
public function createCustomer($email, $firstName = null, $lastName = null, $password = null)
{
    $query = '
        mutation CustomerCreate($input: CustomerCreateInput!) {
            customerCreate(input: $input) {
                customer {
                    id
                    email
                    firstName
                    lastName
                    displayName
                }
                userErrors {
                    field
                    message
                }
            }
        }
    ';
    
    $input = [
        'email' => $email,
    ];
    
    if ($firstName) $input['firstName'] = $firstName;
    if ($lastName) $input['lastName'] = $lastName;
    if ($password) $input['password'] = $password;
    
    $result = $this->graphqlQuery($query, ['input' => $input]);
    
    if ($result && isset($result['customerCreate']['customer'])) {
        return $result['customerCreate']['customer'];
    }
    
    return null;
}

/**
 * Get Customer by Email
 */
public function getCustomerByEmail($email)
{
    $query = '
        query GetCustomer($email: String!) {
            customers(first: 1, query: "email:' . $email . '") {
                edges {
                    node {
                        id
                        email
                        firstName
                        lastName
                        displayName
                        phone
                        ordersCount
                        totalSpent {
                            amount
                        }
                    }
                }
            }
        }
    ';
    
    $result = $this->graphqlQuery($query, ['email' => $email]);
    
    if ($result && isset($result['customers']['edges'][0]['node'])) {
        return $result['customers']['edges'][0]['node'];
    }
    
    return null;
}

/**
 * Login Customer
 */
public function loginCustomer($email, $password)
{
    $query = '
        mutation CustomerAccessTokenCreate($input: CustomerAccessTokenCreateInput!) {
            customerAccessTokenCreate(input: $input) {
                customerAccessToken {
                    accessToken
                    expiresAt
                }
                userErrors {
                    field
                    message
                }
            }
        }
    ';
    
    $result = $this->graphqlQuery($query, [
        'input' => [
            'email' => $email,
            'password' => $password
        ]
    ]);
    
    if ($result && isset($result['customerAccessTokenCreate']['customerAccessToken'])) {
        return $result['customerAccessTokenCreate']['customerAccessToken'];
    }
    
    return null;
}

/**
 * Get Customer by Access Token
 */
public function getCustomerByToken($accessToken)
{
    $query = '
        query GetCustomer($accessToken: String!) {
            customer(customerAccessToken: $accessToken) {
                id
                email
                firstName
                lastName
                displayName
                phone
                ordersCount
                totalSpent {
                    amount
                }
                addresses(first: 5) {
                    edges {
                        node {
                            id
                            address1
                            address2
                            city
                            province
                            country
                            zip
                        }
                    }
                }
                orders(first: 10) {
                    edges {
                        node {
                            id
                            orderNumber
                            createdAt
                            totalPrice {
                                amount
                                currencyCode
                            }
                            financialStatus
                            fulfillmentStatus
                        }
                    }
                }
            }
        }
    ';
    
    $result = $this->graphqlQuery($query, ['accessToken' => $accessToken]);
    
    if ($result && isset($result['customer'])) {
        return $result['customer'];
    }
    
    return null;
}
}