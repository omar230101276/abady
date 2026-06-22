@extends('layouts.admin')

@section('title', 'Manually Create Booking')

@section('content')
<div class="space-y-8 animate-fade-in">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-stone-950 uppercase tracking-wider">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Bookings
        </a>
    </div>

    <div class="max-w-2xl bg-white border border-stone-200 rounded-xl p-8 shadow-sm">
        <div class="flex items-center gap-2 mb-6">
            <i data-lucide="plus-circle" class="w-5 h-5 text-stone-500"></i>
            <h2 class="font-syne font-bold text-stone-900 text-lg">New Booking Form</h2>
        </div>

        <form action="{{ route('admin.bookings.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Client Details Section -->
            <div class="space-y-4">
                <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-widest font-syne border-b border-stone-100 pb-2">Client Information</span>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Client Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                            placeholder="Sarah Connor">
                        @error('name')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                            placeholder="sarah@example.com">
                        @error('email')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Phone Number (Optional)</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                        placeholder="+20 100 000 0000">
                    @error('phone')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Booking Settings Section -->
            <div class="space-y-4 pt-4">
                <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-widest font-syne border-b border-stone-100 pb-2">Session Details</span>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="session_type" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Session Category</label>
                        <select id="session_type" name="session_type" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
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

                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Initial Status</label>
                        <select id="status" name="status" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
                            <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="declined" {{ old('status') === 'declined' ? 'selected' : '' }}>Declined</option>
                            <option value="canceled" {{ old('status') === 'canceled' ? 'selected' : '' }}>Canceled / Archived</option>
                        </select>
                        @error('status')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="booking_date" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Preferred Date</label>
                        <input type="date" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
                        @error('booking_date')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time_slot_id" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Preferred Time Slot</label>
                        <select id="time_slot_id" name="time_slot_id" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
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
            </div>

            <!-- Notes Section -->
            <div class="space-y-4 pt-4">
                <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-widest font-syne border-b border-stone-100 pb-2">Internal / Session Notes</span>
                
                <div>
                    <label for="message" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Inquiry Notes (Optional)</label>
                    <textarea id="message" name="message" rows="4"
                        class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none"
                        placeholder="Add client constraints, requests, or internal logistics details...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-4 border-t border-stone-150 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors shadow-sm">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Create Booking
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="px-6 py-3 border border-stone-200 hover:bg-stone-50 text-stone-700 hover:text-stone-950 font-bold uppercase tracking-wider text-xs rounded-lg transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const blockedDates = @json($blockedDates ?? []);
        const dateInput = document.getElementById('booking_date');
        
        // Prevent selection of blocked dates
        dateInput.addEventListener('input', function() {
            const selectedDate = this.value;
            if (blockedDates.includes(selectedDate)) {
                alert('Warning: The selected date is currently blocked on the studio calendar.');
                this.value = '';
            }
        });
    });
</script>
@endsection
