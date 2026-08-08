<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ShopifyService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CustomerController extends Controller
{
    protected $shopify;
    
    public function __construct(ShopifyService $shopify)
    {
        $this->shopify = $shopify;
    }
    
    /**
     * Show Login Page
     */
    public function index()
    {
        try {
            if (session()->has('customer_access_token')) {
                return redirect()->route('account.dashboard');
            }
            
            return view('account.index');
        } catch (\Exception $e) {
            Log::error('Account index error: ' . $e->getMessage());
            return view('account.index')->with('error', 'Something went wrong.');
        }
    }
    
    /**
     * Login with Email & Password
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);
            
            $result = $this->shopify->loginCustomer($request->email, $request->password);
            
            if ($result && isset($result['accessToken'])) {
                session()->put('customer_access_token', $result['accessToken']);
                session()->put('customer_email', $request->email);
                session()->put('customer_expires_at', $result['expiresAt'] ?? now()->addHours(24));
                
                return redirect()->route('account.dashboard')
                    ->with('success', 'Welcome back!');
            }
            
            return back()->with('error', 'Invalid email or password');
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->with('error', 'Login failed. Please try again.');
        }
    }
    
    /**
     * Passwordless Login - Request OTP
     */
    public function requestOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);
            
            $email = $request->email;
            
            // Generate OTP
            $otp = rand(100000, 999999);
            
            // Store in session with expiry
            session()->put('otp_' . $email, [
                'code' => $otp,
                'expires' => now()->addMinutes(5)->timestamp
            ]);
            
            // In production, send email here
            // Mail::to($email)->send(new OtpMail($otp));
            
            // For development, log OTP
            Log::info('OTP for ' . $email . ': ' . $otp);
            
            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email!',
                'otp' => $otp // Remove in production
            ]);
            
        } catch (\Exception $e) {
            Log::error('Request OTP error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP'
            ], 500);
        }
    }
    
    /**
     * Verify OTP and Login
     */
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|string|size:6'
            ]);
            
            $email = $request->email;
            $otp = $request->otp;
            
            $stored = session()->get('otp_' . $email);
            
            if (!$stored) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP expired. Please request a new one.'
                ], 400);
            }
            
            if (now()->timestamp > $stored['expires']) {
                session()->forget('otp_' . $email);
                return response()->json([
                    'success' => false,
                    'message' => 'OTP expired. Please request a new one.'
                ], 400);
            }
            
            if ($stored['code'] != $otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP. Please try again.'
                ], 400);
            }
            
            // OTP verified - login or create customer
            $customer = $this->shopify->getCustomerByEmail($email);
            
            if (!$customer) {
                // Create new customer
                $customer = $this->shopify->createCustomer($email);
            }
            
            if ($customer) {
                session()->put('customer_access_token', 'passwordless_' . $customer['id'] . '_' . time());
                session()->put('customer_email', $email);
                session()->put('customer_expires_at', now()->addHours(24)->toIso8601String());
                
                session()->forget('otp_' . $email);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to create account. Please try again.'
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Verify OTP error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Verification failed. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Customer Dashboard
     */
    public function dashboard()
    {
        try {
            $token = session()->get('customer_access_token');
            $email = session()->get('customer_email');
            
            if (!$token) {
                return redirect()->route('account.index')->with('error', 'Please login first.');
            }
            
            // Get customer data
            $customer = $this->getCustomerData($email);
            
            if (!$customer) {
                session()->forget('customer_access_token');
                session()->forget('customer_email');
                return redirect()->route('account.index')->with('error', 'Session expired. Please login again.');
            }
            
            // Get orders
            $orders = $this->getCustomerOrders($email);
            
            return view('account.dashboard', [
                'customer' => $customer,
                'orders' => $orders
            ]);
            
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->route('account.index')->with('error', 'Something went wrong.');
        }
    }
    
    /**
     * Get Customer Data
     */
    protected function getCustomerData($email)
    {
        try {
            return $this->shopify->getCustomerByEmail($email);
        } catch (\Exception $e) {
            Log::error('Get customer error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get Customer Orders
     */
    protected function getCustomerOrders($email)
    {
        try {
            $store = config('shopify.store');
            $token = config('shopify.access_token');
            
            // Use Admin API to get orders by email
            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $token,
                'Content-Type' => 'application/json',
            ])->get("https://{$store}/admin/api/2024-07/orders.json", [
                'email' => $email,
                'status' => 'any',
                'limit' => 20
            ]);
            
            if ($response->successful()) {
                return $response->json()['orders'] ?? [];
            }
            
            return [];
            
        } catch (\Exception $e) {
            Log::error('Get orders error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        try {
            session()->forget('customer_access_token');
            session()->forget('customer_email');
            session()->forget('customer_expires_at');
            
            return redirect()->route('home')->with('success', 'Logged out successfully.');
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Logout failed.');
        }
    }
}