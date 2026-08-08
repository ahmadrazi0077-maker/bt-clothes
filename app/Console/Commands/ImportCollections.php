<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ShopifyService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImportCollections extends Command
{
    protected $signature = 'shopify:import-collections';
    protected $description = 'Import collections from Shopify store';

    protected $shopify;

    public function __construct(ShopifyService $shopify)
    {
        parent::__construct();
        $this->shopify = $shopify;
    }

    public function handle()
    {
        $this->info('🔄 Importing collections from Shopify...');

        try {
            $collections = $this->shopify->getAllCollections(50);

            if ($collections) {
                Cache::put('shopify_collections', $collections, 3600);
                Cache::put('all_collections', $collections, 3600);
                Cache::put('homepage_collections', array_slice($collections, 0, 8), 3600);

                $this->info('✅ Imported ' . count($collections) . ' collections successfully!');
                
                // Display summary
                $this->table(
                    ['Name', 'Products', 'Handle'],
                    array_map(function($c) {
                        return [$c['title'], $c['productCount'] ?? 0, $c['handle']];
                    }, $collections)
                );
            } else {
                $this->error('❌ Failed to import collections');
            }
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            Log::error('Import collections error: ' . $e->getMessage());
        }
    }
}