<?php

namespace App\Http\Controllers;

use App\Services\ShopifyService;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    protected $shopify;
    
    public function __construct(ShopifyService $shopify)
    {
        $this->shopify = $shopify;
    }
    
    public function index()
    {
        try {
            $posts = $this->shopify->getBlogPosts(12);
            
            return view('blog.index', [
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            Log::error('Blog error: ' . $e->getMessage());
            return view('blog.index', ['posts' => []]);
        }
    }
    
    public function show($handle)
    {
        try {
            $post = $this->shopify->getBlogPost($handle);
            
            if (!$post) {
                abort(404);
            }
            
            // Get related posts
            $posts = $this->shopify->getBlogPosts(4);
            $related = array_filter($posts, function($p) use ($handle) {
                return $p['handle'] !== $handle;
            });
            
            return view('blog.post', [
                'post' => $post,
                'related' => array_slice($related, 0, 3)
            ]);
        } catch (\Exception $e) {
            Log::error('Blog post error: ' . $e->getMessage());
            abort(404);
        }
    }
}