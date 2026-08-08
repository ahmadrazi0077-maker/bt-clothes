@extends('layouts.app')

@section('title', 'Sign In')
@section('description', 'Sign in to your account.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-light">Sign <span class="font-bold">In</span></h1>
            <p class="text-gray-600 mt-2">Sign in or create an account</p>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- ✅ Shopify Official Sign-in -->
        <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
            
            <!-- ✅ Continue with Shop Button -->
            <a href="https://bin-tariq-collection.myshopify.com/account/login" 
               class="w-full flex items-center justify-center gap-3 bg-black text-white py-3.5 rounded-lg font-semibold hover:bg-gray-800 transition shadow-sm text-base">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                    <path d="M12 6c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"/>
                </svg>
                Continue with Shop
            </a>
            
            <!-- ✅ Continue with Google Button -->
            <a href="https://bin-tariq-collection.myshopify.com/account/login" 
               class="w-full flex items-center justify-center gap-3 bg-white border border-gray-300 text-gray-700 py-3.5 rounded-lg font-semibold hover:bg-gray-50 transition shadow-sm mt-3 text-base">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
            </a>
            
            <!-- Divider -->
            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 border-t border-gray-200"></div>
                <span class="text-sm text-gray-400">or</span>
                <div class="flex-1 border-t border-gray-200"></div>
            </div>
            
            <!-- ✅ Email Login -->
            <form action="{{ route('account.login') }}" method="POST">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Email</label>
                        <input type="email" name="email" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent" 
                               placeholder="Enter your email"
                               required>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="newsletter" id="newsletter" checked>
                        <label for="newsletter">Email me with news and offers</label>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition text-base">
                        Continue with Email
                    </button>
                </div>
            </form>
            
            <!-- Privacy Policy -->
            <div class="mt-4 text-center text-xs text-gray-400">
                By continuing, you agree to our 
                <a href="/pages/privacy" class="text-gray-600 hover:underline">Privacy Policy</a>
            </div>
        </div>
    </div>
</div>
@endsection