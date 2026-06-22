@extends('layouts.admin')

@section('title', 'Edit Booking - ' . $booking->reference_number)

@section('content')
<div class="space-y-8 animate-fade-in">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-stone-950 uppercase tracking-wider">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Bookings
        </a>
    </div>

    <div class="max-w-2xl bg-white border border-stone-200 rounded-xl p-8 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <i data-lucide="edit-3" class="w-5 h-5 text-stone-500"></i>
                <h2 class="font-syne font-bold text-stone-900 text-lg">Edit Booking: {{ $booking->reference_number }}</h2>
            </div>
            @if($booking->trashed())
                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full bg-rose-50 text-rose-700 border border-rose-100">Canceled / Archived</span>
            @endif
        </div>

        <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Client Details Section -->
            <div class="space-y-4">
                <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-widest font-syne border-b border-stone-100 pb-2">Client Information</span>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Client Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $booking->name) }}" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                            placeholder="Sarah Connor">
                        @error('name')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $booking->email) }}" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all"
                            placeholder="sarah@example.com">
                        @error('email')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Phone Number (Optional)</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $booking->phone) }}"
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
                            <option value="" disabled>Select session type</option>
                            <option value="Portrait Session" {{ old('session_type', $booking->session_type) === 'Portrait Session' ? 'selected' : '' }}>Portrait Session</option>
                            <option value="Fashion Editorial" {{ old('session_type', $booking->session_type) === 'Fashion Editorial' ? 'selected' : '' }}>Fashion Editorial</option>
                            <option value="Commercial Campaign" {{ old('session_type', $booking->session_type) === 'Commercial Campaign' ? 'selected' : '' }}>Commercial Campaign</option>
                            <option value="Cinematography / Film B-Roll" {{ old('session_type', $booking->session_type) === 'Cinematography / Film B-Roll' ? 'selected' : '' }}>Cinematography / Film B-Roll</option>
                            <option value="Event Coverage" {{ old('session_type', $booking->session_type) === 'Event Coverage' ? 'selected' : '' }}>Event Coverage</option>
                        </select>
                        @error('session_type')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Booking Status</label>
                        <select id="status" name="status" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
                            <option value="verification_pending" {{ old('status', $booking->status) === 'verification_pending' ? 'selected' : '' }}>Verification Pending</option>
                            <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="approved" {{ old('status', $booking->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="declined" {{ old('status', $booking->status) === 'declined' ? 'selected' : '' }}>Declined</option>
                            <option value="canceled" {{ old('status', $booking->status) === 'canceled' ? 'selected' : '' }}>Canceled / Archived</option>
                        </select>
                        @error('status')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="booking_date" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Preferred Date</label>
                        <input type="date" id="booking_date" name="booking_date" value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
                        @error('booking_date')
                            <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time_slot_id" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Preferred Time Slot</label>
                        <select id="time_slot_id" name="time_slot_id" required
                            class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
                            <option value="" disabled>Select a time slot</option>
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot->id }}" {{ old('time_slot_id', $booking->time_slot_id) == $slot->id ? 'selected' : '' }}>
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
                <span class="block text-[10px] text-stone-400 font-bold uppercase tracking-widest font-syne border-b border-stone-100 pb-2">Client Notes</span>
                
                <div>
                    <label for="message" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Inquiry Message from Client</label>
                    <textarea id="message" name="message" rows="3"
                        class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none"
                        placeholder="Client did not provide any message...">{{ old('message', $booking->message) }}</textarea>
                    @error('message')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Admin Response Section -->
            <div class="space-y-4 pt-4">
                <span class="block text-[10px] text-amber-600 font-bold uppercase tracking-widest font-syne border-b border-amber-100 pb-2">Photographer's Response (Visible to Client in Portal)</span>
                
                <div>
                    <label for="admin_response" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Your response / notes to the client</label>
                    <textarea id="admin_response" name="admin_response" rows="4"
                        class="w-full px-4 py-3 bg-amber-50/30 border border-amber-200/60 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-amber-400 focus:bg-white transition-all resize-none"
                        placeholder="Write details like: package details, instructions, confirmation response, pricing notes etc. This will display on the Client Portal.">{{ old('admin_response', $booking->admin_response) }}</textarea>
                    @error('admin_response')
                        <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-4 border-t border-stone-150 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Changes
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
        const initialDate = "{{ $booking->booking_date->format('Y-m-d') }}";
        
        // Prevent selection of blocked dates
        dateInput.addEventListener('input', function() {
            const selectedDate = this.value;
            if (selectedDate !== initialDate && blockedDates.includes(selectedDate)) {
                alert('Warning: The selected date is currently blocked on the studio calendar.');
                this.value = initialDate;
            }
        });
    });
</script>
@endsection
