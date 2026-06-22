@extends('layouts.app')

@section('title', 'Inquire & Booking | Abady')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Header Title -->
    <div class="mb-12 border-b border-stone-200 pb-8">
        <span class="text-xs font-bold text-amber-705 uppercase tracking-widest block">Get In Touch</span>
        <h1 class="font-syne text-4xl font-extrabold text-stone-900 mt-2">STUDIO INQUIRIES</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
        <!-- Direct Contacts info -->
        <div class="lg:col-span-5 space-y-8">
            <div class="space-y-4">
                <h3 class="font-syne text-2xl font-bold text-stone-950">Let's discuss your visual project.</h3>
                <p class="text-stone-550 text-sm leading-relaxed font-light">
                    For campaign bookings, high-fashion editorials, wedding enquiries, or print sales, please fill out the contact form or send a message directly to our studio inbox.
                </p>
            </div>

            <div class="space-y-6">
                <!-- Info Item 1 -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full border border-stone-200 bg-white flex items-center justify-center text-amber-700 flex-shrink-0 shadow-sm">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-bold uppercase text-stone-400 tracking-wider">Email Address</span>
                        <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'hello@abady.com') }}" class="text-sm font-semibold text-stone-900 hover:text-amber-750 transition-colors">
                            {{ \App\Models\Setting::get('contact_email', 'hello@abady.com') }}
                        </a>
                    </div>
                </div>

                <!-- Info Item 2 -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full border border-stone-200 bg-white flex items-center justify-center text-amber-700 flex-shrink-0 shadow-sm">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-bold uppercase text-stone-400 tracking-wider">Location</span>
                        <span class="text-sm font-semibold text-stone-900">
                            Cairo, Egypt (Operating Globally)
                        </span>
                    </div>
                </div>

                <!-- Info Item 3 -->
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full border border-stone-200 bg-white flex items-center justify-center text-amber-700 flex-shrink-0 shadow-sm">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-bold uppercase text-stone-400 tracking-wider">Response Rate</span>
                        <span class="text-sm font-semibold text-stone-900">
                            Within 24-48 business hours
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form (Right column) -->
        <div class="lg:col-span-7 bg-white border border-stone-200 p-8 rounded-2xl shadow-sm">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-6 rounded-xl flex items-start gap-3">
                    <i data-lucide="check" class="w-6 h-6 text-emerald-605 flex-shrink-0 mt-0.5 bg-emerald-100 p-1 rounded-full"></i>
                    <div class="space-y-1">
                        <h4 class="font-bold text-sm">Message Sent!</h4>
                        <p class="text-xs text-emerald-700 leading-relaxed">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Your Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all @error('name') border-rose-500 @enderror"
                        placeholder="e.g. Sarah Connor">
                    @error('name')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all @error('email') border-rose-500 @enderror"
                        placeholder="e.g. sarah@example.com">
                    @error('email')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Phone Number (Optional)</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all @error('phone') border-rose-500 @enderror"
                        placeholder="e.g. +20 100 000 0000">
                    @error('phone')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Describe Your Project</label>
                    <textarea id="message" name="message" rows="6" required
                        class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none @error('message') border-rose-500 @enderror"
                        placeholder="Tell us about the project details, styling ideas, location coordinates, dates..."></textarea>
                    @error('message')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full py-3.5 px-6 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i data-lucide="send" class="w-4 h-4"></i> Dispatch Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
