<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BlogController;

// ============================================
// HOME
// ============================================

Route::get('/', [ShopifyController::class, 'home'])->name('home');

// ============================================
// PRODUCTS
// ============================================

Route::get('/products', [ShopifyController::class, 'products'])->name('products');
Route::get('/product/{handle}', [ShopifyController::class, 'product'])->name('product.show');

// ============================================
// COLLECTIONS
// ============================================

Route::get('/collections', [ShopifyController::class, 'collections'])->name('collections');
Route::get('/collections/{handle}', [ShopifyController::class, 'collection'])->name('collection.show');
Route::get('/categories', [ShopifyController::class, 'categories'])->name('categories');

// ============================================
// SEARCH
// ============================================

Route::get('/search', [ShopifyController::class, 'search'])->name('search');

// ============================================
// CART
// ============================================



Route::get('/cart', [ShopifyController::class, 'cart'])
    ->name('cart');

Route::post('/cart/add', [ShopifyController::class, 'addToCart'])
    ->name('cart.add');

Route::post('/cart/update', [ShopifyController::class, 'updateCart'])
    ->name('cart.update');

Route::post('/cart/remove', [ShopifyController::class, 'removeFromCart'])
    ->name('cart.remove');

Route::post('/cart/clear', [ShopifyController::class, 'clearCart'])
    ->name('cart.clear');

Route::get('/cart/count', [ShopifyController::class, 'cartCount'])
    ->name('cart.count');

// Wishlist




Route::post('/shopify/cart/add', [
    ShopifyController::class,
    'addToShopifyCart'
])->name('shopify.cart.add');

// ============================================
// API ROUTES
// ============================================

// CHECKOUT
// ============================================

Route::get('/checkout', [ShopifyController::class, 'checkout'])->name('checkout');

// ============================================
// ✅ CUSTOMER ACCOUNT ROUTES (ADD THIS)
// ============================================

// Route::prefix('account')->group(function () {
//     Route::get('/', [CustomerController::class, 'index'])->name('account.index');
//     Route::post('/login', [CustomerController::class, 'login'])->name('account.login');
//     Route::post('/register', [CustomerController::class, 'register'])->name('account.register');
//     Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('account.dashboard');
//     Route::get('/logout', [CustomerController::class, 'logout'])->name('account.logout');
// });

// ============================================
// BLOG
// ============================================

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{handle}', [BlogController::class, 'show'])->name('blog.show');

use App\Http\Controllers\GoogleController;

// ============================================
// GOOGLE LOGIN ROUTES
// ============================================

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// ============================================
// SHOP LOGIN (Shop Pay)
// ============================================

Route::get('/auth/shop', [CustomerController::class, 'shopLogin'])->name('shop.login');

// ============================================
// CUSTOMER ACCOUNT ROUTES
// ============================================
// ============================================
// NEWSLETTER ROUTE
// ============================================

use App\Http\Controllers\NewsletterController;

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

use App\Http\Controllers\ApiController;

// ============================================
// API ROUTES
// ============================================

Route::get('/api/product/{id}', [ApiController::class, 'getProduct']);
Route::get('/api/recommendations/{productId}', [ApiController::class, 'getRecommendations']);
// Product route
Route::get('/product/{handle}', [ShopifyController::class, 'product'])->name('product.show');
// ============================================
// PAGES
// ============================================

Route::get('/pages/{page}', function ($page) {
    $allowed = ['about', 'contact', 'faq', 'shipping', 'returns', 'size-guide', 'privacy', 'terms', 'cookies'];
    if (in_array($page, $allowed)) {
        return view('pages.' . $page);
    }
    abort(404);
})->name('page');


