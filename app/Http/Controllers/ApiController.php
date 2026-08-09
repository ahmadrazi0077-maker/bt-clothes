<?php

namespace App\Http\Controllers;

use App\Services\ShopifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    protected $shopify;
    
    public function __construct(ShopifyService $shopify)
    {
        $this->shopify = $shopify;
    }
    
    /**
     * Get Single Product by ID
     */
    public function getProduct($id)
    {
        try {
            // Clean the ID if it has the full URI format
            $cleanId = str_replace('gid://shopify/Product/', '', $id);
            $cleanId = str_replace('gid://shopify/ProductVariant/', '', $cleanId);
            
            Log::info('API Product request:', ['id' => $id, 'cleanId' => $cleanId]);
            
            // Try to get product from Shopify
            $product = $this->shopify->getProductById($id);
            
            if (!$product) {
                $product = $this->shopify->getProductById($cleanId);
            }
            
            if (!$product) {
                // Try by handle if ID is actually a handle
                $product = $this->shopify->getProductByHandle($id);
            }
            
            if ($product) {
                Log::info('Product found:', ['title' => $product['title'] ?? '']);
                return response()->json($product);
            }
            
            // Return mock data if product not found
            Log::warning('Product not found, returning mock:', ['id' => $id]);
            return response()->json($this->getMockProduct($id));
            
        } catch (\Exception $e) {
            Log::error('API Product error: ' . $e->getMessage());
            return response()->json($this->getMockProduct($id));
        }
    }
    
    /**
     * Get Product Recommendations
     */
    public function getRecommendations($productId)
    {
        try {
            $recommendations = $this->shopify->getProductRecommendations($productId);
            
            if ($recommendations && count($recommendations) > 0) {
                return response()->json($recommendations);
            }
            
            return response()->json($this->getMockRecommendations($productId));
            
        } catch (\Exception $e) {
            Log::error('Recommendations error: ' . $e->getMessage());
            return response()->json($this->getMockRecommendations($productId));
        }
    }
    
    /**
     * Get Wishlist Products
     */
    public function getWishlistProducts(Request $request)
    {
        try {
            $productIds = $request->input('ids', []);
            
            if (empty($productIds)) {
                return response()->json([]);
            }
            
            $products = [];
            foreach ($productIds as $id) {
                $product = $this->shopify->getProductById($id);
                if ($product) {
                    $products[] = $product;
                }
            }
            
            return response()->json($products);
            
        } catch (\Exception $e) {
            Log::error('Wishlist API error: ' . $e->getMessage());
            return response()->json([]);
        }
    }
    
    // ============================================
    // MOCK DATA
    // ============================================
    
    protected function getMockProduct($id)
    {
        $index = abs(crc32($id) % 100) + 1;
        return [
            'id' => $id,
            'title' => 'Mock Product ' . $index,
            'handle' => 'mock-product-' . $index,
            'description' => 'This is a mock product for testing purposes.',
            'availableForSale' => true,
            'vendor' => 'BT Clothes',
            'priceRange' => [
                'minVariantPrice' => [
                    'amount' => number_format(rand(20, 100), 2),
                    'currencyCode' => 'PKR'
                ]
            ],
            'compareAtPriceRange' => rand(0, 1) ? [
                'minVariantPrice' => [
                    'amount' => number_format(rand(50, 150), 2),
                    'currencyCode' => 'PKR'
                ]
            ] : null,
            'images' => [
                'edges' => []
            ],
            'variants' => [
                'edges' => [
                    [
                        'node' => [
                            'id' => 'mock_variant_' . $index,
                            'title' => 'Default Title',
                            'price' => [
                                'amount' => number_format(rand(20, 100), 2),
                                'currencyCode' => 'PKR'
                            ],
                            'availableForSale' => true,
                            'selectedOptions' => [
                                ['name' => 'Color', 'value' => 'Black'],
                                ['name' => 'Size', 'value' => 'M']
                            ]
                        ]
                    ]
                ]
            ],
            'options' => [
                ['name' => 'Color', 'values' => ['Black', 'White', 'Gray']],
                ['name' => 'Size', 'values' => ['S', 'M', 'L', 'XL']]
            ]
        ];
    }
    
    protected function getMockRecommendations($productId)
    {
        $recommendations = [];
        for ($i = 1; $i <= 4; $i++) {
            $recommendations[] = [
                'id' => 'rec_' . $i,
                'title' => 'Recommended Product ' . $i,
                'handle' => 'recommended-product-' . $i,
                'priceRange' => [
                    'minVariantPrice' => [
                        'amount' => number_format(rand(25, 80), 2),
                        'currencyCode' => 'PKR'
                    ]
                ],
                'images' => [
                    'edges' => []
                ],
                'availableForSale' => true
            ];
        }
        return $recommendations;
    }
}