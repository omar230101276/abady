@extends('layouts.app')

@section('title', 'Book a Photography Session | Abady')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    <!-- Header Title -->
    <div class="mb-12 border-b border-stone-200 pb-8">
        <span class="text-xs font-bold text-amber-705 uppercase tracking-widest block">Online Reservations</span>
        <h1 class="font-syne text-4xl font-extrabold text-stone-900 mt-2">BOOK A SESSION</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
        <!-- Info Column -->
        <div class="lg:col-span-5 space-y-8">
            <div class="space-y-4">
                <h3 class="font-syne text-2xl font-bold text-stone-950">Secure your session today.</h3>
                <p class="text-stone-550 text-sm leading-relaxed font-light">
                    Select your preferred date, session category, and enter your details to request a session booking. 
                </p>
                <p class="text-stone-550 text-sm leading-relaxed font-light">
                    Abady's studio will review the date availability and get back to you with booking packages, styling boards, and confirmation details within 24-48 business hours.
                </p>
            </div>

            <!-- Predefined Session Packages list -->
            <div class="p-6 bg-stone-50 border border-stone-200 rounded-xl space-y-4">
                <h4 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider">Session Categories</h4>
                <ul class="text-xs text-stone-600 space-y-3 font-medium">
                    <li class="flex items-center gap-2">
                        <i data-lucide="camera" class="w-4 h-4 text-amber-700 flex-shrink-0"></i>
                        <span>Portrait Session — Editorial headshots & artistic portraiture</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="layers" class="w-4 h-4 text-amber-700 flex-shrink-0"></i>
                        <span>Fashion Editorial — Agency submissions & designer lookbooks</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="award" class="w-4 h-4 text-amber-700 flex-shrink-0"></i>
                        <span>Commercial Campaign — Brand catalog & marketing visuals</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="clapperboard" class="w-4 h-4 text-amber-700 flex-shrink-0"></i>
                        <span>Cinematography — High-end cinematic reels & B-roll</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-amber-700 flex-shrink-0"></i>
                        <span>Event Coverage — Fine-art reportages & documentaries</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Booking Request Form -->
        <div class="lg:col-span-7 bg-white border border-stone-200 p-8 rounded-2xl shadow-sm">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-6 rounded-xl space-y-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <i data-lucide="check" class="w-6 h-6 text-emerald-605 flex-shrink-0 mt-0.5 bg-emerald-100 p-1 rounded-full"></i>
                        <div class="space-y-1">
                            <h4 class="font-bold text-sm">Booking Request Submitted!</h4>
                            <p class="text-xs text-emerald-700 leading-relaxed">{{ session('success') }}</p>
                        </div>
                    </div>
                    
                    @if(session('booking_reference'))
                        <div class="bg-white border border-emerald-100 p-4 rounded-lg flex items-center justify-between gap-4 border border-emerald-200">
                            <div>
                                <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-wider font-syne">Your Booking Reference</span>
                                <span class="font-mono text-sm font-bold text-stone-900" id="ref-code">{{ session('booking_reference') }}</span>
                            </div>
                            <button type="button" onclick="copyRefCode()" class="px-3.5 py-2 bg-stone-950 hover:bg-stone-850 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors flex items-center gap-1">
                                <i data-lucide="copy" class="w-3 h-3"></i> <span id="copy-btn-text">Copy Code</span>
                            </button>
                        </div>
                        <script>
                            function copyRefCode() {
                                const refCode = document.getElementById('ref-code').innerText;
                                navigator.clipboard.writeText(refCode).then(() => {
                                    const btnText = document.getElementById('copy-btn-text');
                                    btnText.innerText = 'Copied!';
                                    setTimeout(() => {
                                        btnText.innerText = 'Copy Code';
                                    }, 2000);
                                });
                            }
                        </script>
                    @endif
                </div>
            @endif

            <form action="{{ route('book.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Your Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all @error('name') border-rose-500 @enderror"
                            placeholder="Sarah Connor">
                        @error('name')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all @error('email') border-rose-500 @enderror"
                            placeholder="sarah@example.com">
                        @error('email')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Phone Number (Optional)</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                            placeholder="+20 100 000 0000">
                        @error('phone')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Session Type -->
                    <div>
                        <label for="session_type" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Session Category</label>
                        <select id="session_type" name="session_type" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
                            <option value="" disabled {{ old('session_type') === null ? 'selected' : '' }}>Select session type</option>
                            <option value="Portrait Session" {{ old('session_type') === 'Portrait Session' ? 'selected' : '' }}>Portrait Session</option>
                            <option value="Fashion Editorial" {{ old('session_type') === 'Fashion Editorial' ? 'selected' : '' }}>Fashion Editorial</option>
                            <option value="Commercial Campaign" {{ old('session_type') === 'Commercial Campaign' ? 'selected' : '' }}>Commercial Campaign</option>
                            <option value="Cinematography / Film B-Roll" {{ old('session_type') === 'Cinematography / Film B-Roll' ? 'selected' : '' }}>Cinematography / Film B-Roll</option>
                            <option value="Event Coverage" {{ old('session_type') === 'Event Coverage' ? 'selected' : '' }}>Event Coverage</option>
                        </select>
                        @error('session_type')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label for="booking_date" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Preferred Date</label>
                        <input type="date" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer @error('booking_date') border-rose-500 @enderror">
                        @error('booking_date')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Slot -->
                    <div>
                        <label for="time_slot_id" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Preferred Time Slot</label>
                        <select id="time_slot_id" name="time_slot_id" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer @error('time_slot_id') border-rose-500 @enderror">
                            <option value="" disabled {{ old('time_slot_id') === null ? 'selected' : '' }}>Select a time slot</option>
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot->id }}" {{ old('time_slot_id') == $slot->id ? 'selected' : '' }}>
                                    {{ $slot->name }} ({{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }})
                                </option>
                            @endforeach
                        </select>
                        @error('time_slot_id')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Inquiry / Session Notes</label>
                    <textarea id="message" name="message" rows="5"
                        class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none"
                        placeholder="Share your campaign details, location, dates...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-6 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i data-lucide="calendar" class="w-4 h-4"></i> Send Booking Request
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const blockedDates = @json($blockedDates ?? []);
        const dateInput = document.getElementById('booking_date');
        
        // Prevent selection of blocked dates
        dateInput.addEventListener('input', function() {
            const selectedDate = this.value;
            if (blockedDates.includes(selectedDate)) {
                alert('The photographer is unavailable on this date. Please select a different date.');
                this.value = '';
            }
        });
        
        // Disable selecting past dates
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    });
</script>
@endsection
