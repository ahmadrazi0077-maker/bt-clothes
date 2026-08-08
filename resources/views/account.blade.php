@extends('layouts.app')

@section('title', 'Account')
@section('description', 'Login or create your account.')

@section('content')
<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-light">My <span class="font-bold">Account</span></h1>
            <p class="text-gray-600 mt-2">Login or create an account to manage your orders.</p>
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
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Login Form -->
            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                <h2 class="text-xl font-semibold mb-4">Login</h2>
                <form action="{{ route('account.login') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Email Address</label>
                            <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Password</label>
                            <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                            Login
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Register Form -->
            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                <h2 class="text-xl font-semibold mb-4">Create Account</h2>
                <form action="{{ route('account.register') }}" method="POST">
                    @csrf
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold mb-1">First Name</label>
                                <input type="text" name="first_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Last Name</label>
                                <input type="text" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Email Address</label>
                            <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Password</label>
                            <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900" required>
                        </div>
                        <button type="submit" class="w-full border-2 border-gray-900 text-gray-900 py-3 rounded-lg font-semibold hover:bg-gray-900 hover:text-white transition">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-8 bg-gray-50 rounded-xl p-6 text-center">
            <p class="text-sm text-gray-600">
                💡 When you place an order, an account will be automatically created with your email.
            </p>
        </div>
    </div>
</div>
@endsection