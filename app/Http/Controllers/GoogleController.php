<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\ShopifyService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    protected $shopify;
    
    public function __construct(ShopifyService $shopify)
    {
        $this->shopify = $shopify;
    }
    
    /**
     * Redirect to Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    /**
     * Handle Google Callback
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            if (!$user || !$user->getEmail()) {
                return redirect()->route('account.index')
                    ->with('error', 'Unable to get Google account information.');
            }
            
            $email = $user->getEmail();
            $firstName = $user->getUser()['given_name'] ?? $user->getName();
            $lastName = $user->getUser()['family_name'] ?? '';
            
            // Try to login with existing Shopify customer
            $loginResult = $this->shopify->loginCustomer($email, '');
            
            if ($loginResult && isset($loginResult['accessToken'])) {
                // Existing customer - login
                Session::put('customer_access_token', $loginResult['accessToken']);
                Session::put('customer_expires_at', $loginResult['expiresAt'] ?? now()->addHours(24));
                
                return redirect()->route('account.dashboard')
                    ->with('success', 'Welcome back, ' . $firstName . '!');
            }
            
            // Try to find customer by email
            $existingCustomer = $this->shopify->getCustomerByEmail($email);
            
            if ($existingCustomer) {
                // Customer exists but no password - create access token
                $loginResult = $this->shopify->loginCustomer($email, '');
                
                if ($loginResult && isset($loginResult['accessToken'])) {
                    Session::put('customer_access_token', $loginResult['accessToken']);
                    Session::put('customer_expires_at', $loginResult['expiresAt'] ?? now()->addHours(24));
                    
                    return redirect()->route('account.dashboard')
                        ->with('success', 'Welcome back, ' . $firstName . '!');
                }
            }
            
            // New customer - create account
            $randomPassword = 'GoogleLogin@' . rand(100000, 999999);
            
            $customer = $this->shopify->createCustomer(
                $email,
                $firstName,
                $lastName,
                $randomPassword
            );
            
            if ($customer) {
                // Auto login
                $loginResult = $this->shopify->loginCustomer($email, $randomPassword);
                
                if ($loginResult && isset($loginResult['accessToken'])) {
                    Session::put('customer_access_token', $loginResult['accessToken']);
                    Session::put('customer_expires_at', $loginResult['expiresAt'] ?? now()->addHours(24));
                    
                    return redirect()->route('account.dashboard')
                        ->with('success', 'Account created successfully! Welcome, ' . $firstName . '!');
                }
            }
            
            return redirect()->route('account.index')
                ->with('error', 'Unable to create account. Please try again.');
            
        } catch (\Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('account.index')
                ->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}