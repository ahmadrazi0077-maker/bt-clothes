@php
    $isLoggedIn = session()->has('customer_access_token');
@endphp

<!-- Account Button -->
<a href="{{ $isLoggedIn ? route('account.dashboard') : 'https://bin-tariq-collection.myshopify.com/account/login' }}" 
   class="text-xl hover:text-gray-600 transition relative">
    👤
    @if($isLoggedIn)
        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-green-500 rounded-full"></span>
    @endif
</a>