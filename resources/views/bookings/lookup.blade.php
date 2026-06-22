@extends('layouts.app')

@section('title', 'Manage Booking | Abady')

@section('content')
<div class="max-w-md mx-auto px-6 py-20">
    <div class="bg-white border border-stone-200 p-8 rounded-2xl shadow-sm space-y-6">
        <div class="text-center space-y-2">
            <span class="text-xs font-bold text-amber-700 uppercase tracking-widest block">Client Portal</span>
            <h1 class="font-syne text-3xl font-extrabold text-stone-900">LOOKUP BOOKING</h1>
            <p class="text-stone-500 text-xs font-light">Enter your email and reference number to manage your session</p>
        </div>

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('bookings.lookup') }}" method="POST" class="space-y-4" autocomplete="off">
            @csrf

            <!-- Booking Reference Number -->
            <div>
                <label for="reference_number" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Booking Reference</label>
                <input type="text" id="reference_number" name="reference_number" 
                    value="{{ old('reference_number', session('prefilled_reference')) }}" required autocomplete="off"
                    class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all placeholder-stone-300"
                    placeholder="e.g. ABD-2026-000125">
                @error('reference_number')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                    class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all placeholder-stone-300"
                    placeholder="sarah@example.com">
                <p class="text-[10px] text-stone-400 font-light mt-1">Please enter your email address only (do not append your phone number).</p>
                @error('email')
                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 px-6 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i> Access Booking
            </button>
        </form>
    </div>
</div>
@endsection
