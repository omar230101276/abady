@extends('layouts.app')

@section('title', 'Admin Login | Abady')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md bg-white border border-stone-200 p-8 shadow-sm rounded-2xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="font-syne text-2xl font-extrabold text-stone-950 uppercase tracking-wider">Admin Gate</h2>
            <p class="text-stone-500 text-sm mt-2">Sign in to manage Abady's portfolio content</p>
        </div>

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-400">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-10 pr-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all @error('email') border-rose-500 focus:border-rose-500 @enderror"
                        placeholder="admin@abady.com">
                </div>
                @error('email')
                    <p class="text-rose-600 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-400">
                        <i data-lucide="key-round" class="w-4 h-4"></i>
                    </span>
                    <input type="password" id="password" name="password" required
                        class="w-full pl-10 pr-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                        placeholder="••••••••">
                </div>
                @error('password')
                    <p class="text-rose-600 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember" type="checkbox" name="remember" class="w-4.5 h-4.5 text-stone-900 border-stone-300 rounded focus:ring-stone-900">
                <label for="remember" class="ml-2.5 text-sm text-stone-600 cursor-pointer select-none">Remember this session</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 px-4 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2">
                <i data-lucide="log-in" class="w-4 h-4"></i> Authenticate
            </button>
        </form>
    </div>
</div>
@endsection
