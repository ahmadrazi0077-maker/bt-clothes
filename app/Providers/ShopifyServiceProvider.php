<?php

namespace App\Services;

use Shopify\Clients\Rest;
use Shopify\Clients\Graphql;
use Illuminate\Support\Facades\Log;

class ShopifyService
{
    protected $client;
    protected $graphql;
    protected $store;
    protected $accessToken;
    
    public function __construct()
    {
        // Get credentials from config
        $this->store = config('shopify.store');
        $this->accessToken = config('shopify.access_token');
        
        // Check if credentials are set
        if (empty($this->store) || empty($this->accessToken)) {
            Log::warning('Shopify credentials not configured properly');
            
            // Return a dummy client for development
            $this->client = null;
            $this->graphql = null;
            return;
        }
        
        try {
            // Initialize REST Client
            $this->client = new Rest(
                $this->store,
                $this->accessToken
            );
            
            // Initialize GraphQL Client
            $this->graphql = new Graphql(
                $this->store,
                $this->accessToken
            );
        } catch (\Exception $e) {
            Log::error('Shopify client initialization failed: ' . $e->getMessage());
            $this->client = null;
            $this->graphql = null;
        }
    }
    
    /**
     * Check if client is initialized
     */
    public function isInitialized()
    {
        return $this->client !== null;
    }
    
    /**
     * Get All Products
     */
    public function getProducts($limit = 12, $page = 1, $collection = null)
    {
        if (!$this->isInitialized()) {
            return $this->getMockProducts($limit);
        }
        
        try {
            $params = [
                'limit' => $limit,
                'page' => $page,
                'fields' => 'id,title,handle,variants,images,price,compare_at_price,available,tags'
            ];
            
            if ($collection) {
                $response = $this->client->get("/collections/{$collection}/products", $params);
            } else {
                $response = $this->client->get('/products', $params);
            }
            
            return $response['body']['products'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return $this->getMockProducts($limit);
        }
    }
    
    /**
     * Get Single Product by Handle
     */
    public function getProduct($handle)
    {
        if (!$this->isInitialized()) {
            return $this->getMockProduct($handle);
        }
        
        try {
            $response = $this->client->get("/products/{$handle}", [
                'fields' => 'id,title,handle,variants,images,price,compare_at_price,description,available,tags,vendor,options'
            ]);
            
            return $response['body']['product'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage());
            return $this->getMockProduct($handle);
        }
    }
    
    /**
     * Get Product by ID
     */
    public function getProductById($id)
    {
        if (!$this->isInitialized()) {
            return $this->getMockProduct($id);
        }
        
        try {
            $response = $this->client->get("/products/{$id}");
            return $response['body']['product'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error fetching product by ID: ' . $e->getMessage());
            return $this->getMockProduct($id);
        }
    }
    
    /**
     * Get Collections
     */
    public function getCollections($limit = 20)
    {
        if (!$this->isInitialized()) {
            return $this->getMockCollections($limit);
        }
        
        try {
            $response = $this->client->get('/collections', [
                'limit' => $limit,
                'fields' => 'id,title,handle,image,description'
            ]);
            
            return $response['body']['collections'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error fetching collections: ' . $e->getMessage());
            return $this->getMockCollections($limit);
        }
    }
    
    /**
     * Get Collection Products
     */
    public function getCollectionProducts($handle, $limit = 24)
    {
        if (!$this->isInitialized()) {
            return $this->getMockProducts($limit);
        }
        
        try {
            $response = $this->client->get("/collections/{$handle}/products", [
                'limit' => $limit,
                'fields' => 'id,title,handle,variants,images,price,compare_at_price,available'
            ]);
            
            return $response['body']['products'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error fetching collection products: ' . $e->getMessage());
            return $this->getMockProducts($limit);
        }
    }
    
    /**
     * Search Products
     */
    public function searchProducts($query, $limit = 20)
    {
        if (!$this->isInitialized()) {
            return $this->getMockProducts($limit);
        }
        
        try {
            $response = $this->client->get('/products', [
                'limit' => $limit,
                'query' => $query,
                'fields' => 'id,title,handle,variants,images,price'
            ]);
            
            return $response['body']['products'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error searching products: ' . $e->getMessage());
            return $this->getMockProducts($limit);
        }
    }
    
    /**
     * Create Checkout
     */
    public function createCheckout($lineItems)
    {
        if (!$this->isInitialized()) {
            return [
                'web_url' => '/checkout/mock',
                'id' => 'mock_checkout_id'
            ];
        }
        
        try {
            $response = $this->client->post('/checkouts', [
                'line_items' => $lineItems
            ]);
            
            return $response['body']['checkout'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error creating checkout: ' . $e->getMessage());
            return [
                'web_url' => '/checkout/mock',
                'id' => 'mock_checkout_id'
            ];
        }
    }
    
    /**
     * Get Checkout
     */
    public function getCheckout($checkoutId)
    {
        if (!$this->isInitialized()) {
            return null;
        }
        
        try {
            $response = $this->client->get("/checkouts/{$checkoutId}");
            return $response['body']['checkout'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error fetching checkout: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get Customers
     */
    public function getCustomers($limit = 10)
    {
        if (!$this->isInitialized()) {
            return [];
        }
        
        try {
            $response = $this->client->get('/customers', [
                'limit' => $limit,
                'fields' => 'id,first_name,last_name,email,phone,orders_count,total_spent'
            ]);
            
            return $response['body']['customers'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error fetching customers: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get Customer by Email
     */
    public function getCustomerByEmail($email)
    {
        if (!$this->isInitialized()) {
            return null;
        }
        
        try {
            $response = $this->client->get('/customers', [
                'query' => "email:{$email}"
            ]);
            
            $customers = $response['body']['customers'] ?? [];
            return $customers[0] ?? null;
        } catch (\Exception $e) {
            Log::error('Error fetching customer: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create Customer
     */
    public function createCustomer($data)
    {
        if (!$this->isInitialized()) {
            return null;
        }
        
        try {
            $response = $this->client->post('/customers', [
                'customer' => $data
            ]);
            
            return $response['body']['customer'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get Orders
     */
    public function getOrders($limit = 10, $status = 'any')
    {
        if (!$this->isInitialized()) {
            return [];
        }
        
        try {
            $params = [
                'limit' => $limit,
                'fields' => 'id,order_number,created_at,financial_status,fulfillment_status,total_price,line_items'
            ];
            
            if ($status !== 'any') {
                $params['status'] = $status;
            }
            
            $response = $this->client->get('/orders', $params);
            return $response['body']['orders'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get Order by ID
     */
    public function getOrder($orderId)
    {
        if (!$this->isInitialized()) {
            return null;
        }
        
        try {
            $response = $this->client->get("/orders/{$orderId}");
            return $response['body']['order'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error fetching order: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create Webhook
     */
    public function createWebhook($topic, $address, $format = 'json')
    {
        if (!$this->isInitialized()) {
            return null;
        }
        
        try {
            $response = $this->client->post('/webhooks', [
                'webhook' => [
                    'topic' => $topic,
                    'address' => $address,
                    'format' => $format
                ]
            ]);
            
            return $response['body']['webhook'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error creating webhook: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * GraphQL Query
     */
    public function graphqlQuery($query, $variables = [])
    {
        if (!$this->isInitialized()) {
            return null;
        }
        
        try {
            $response = $this->graphql->query([
                'query' => $query,
                'variables' => $variables
            ]);
            
            return $response['body']['data'] ?? null;
        } catch (\Exception $e) {
            Log::error('Error executing GraphQL query: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get Product Recommendations (GraphQL)
     */
    public function getRecommendations($productId)
    {
        if (!$this->isInitialized()) {
            return $this->getMockProducts(4);
        }
        
        try {
            $query = '
                query productRecommendations($productId: ID!) {
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
            return $result['productRecommendations'] ?? [];
        } catch (\Exception $e) {
            Log::error('Error getting recommendations: ' . $e->getMessage());
            return $this->getMockProducts(4);
        }
    }
    
    // ============================================
    // MOCK DATA FOR DEVELOPMENT (Fallback)
    // ============================================
    
    protected function getMockProducts($limit = 12)
    {
        $products = [];
        for ($i = 1; $i <= $limit; $i++) {
            $products[] = [
                'id' => "mock_product_{$i}",
                'title' => "Mock Product {$i}",
                'handle' => "mock-product-{$i}",
                'description' => "This is a mock product for development purposes.",
                'available' => true,
                'vendor' => 'Sanctuary Flow',
                'tags' => ['mock', 'development'],
                'variants' => [
                    [
                        'id' => "mock_variant_{$i}",
                        'title' => "Default Title",
                        'price' => number_format(50 * $i, 2),
                        'available' => true
                    ]
                ],
                'images' => [],
                'compare_at_price' => $i % 2 == 0 ? number_format(50 * $i * 1.5, 2) : null
            ];
        }
        return $products;
    }
    
    protected function getMockProduct($handle)
    {
        return [
            'id' => 'mock_product_1',
            'title' => 'Mock Product',
            'handle' => $handle,
            'description' => 'This is a mock product for development purposes.',
            'available' => true,
            'vendor' => 'Sanctuary Flow',
            'tags' => ['mock', 'development'],
            'variants' => [
                [
                    'id' => 'mock_variant_1',
                    'title' => 'Default Title',
                    'price' => '89.00',
                    'available' => true
                ]
            ],
            'images' => [],
            'compare_at_price' => '120.00'
        ];
    }
    
    protected function getMockCollections($limit = 20)
    {
        $collections = [];
        for ($i = 1; $i <= $limit; $i++) {
            $collections[] = [
                'id' => "mock_collection_{$i}",
                'title' => "Mock Collection {$i}",
                'handle' => "mock-collection-{$i}",
                'description' => "This is a mock collection.",
                'image' => null
            ];
        }
        return $collections;
    }
}