@extends('layouts.app')

@section('title', 'Manage Booking ' . $booking->reference_number . ' | Abady')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <!-- Header -->
    <div class="mb-12 border-b border-stone-200 pb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
        <div>
            <span class="text-xs font-bold text-amber-705 uppercase tracking-widest block">Booking Portal</span>
            <h1 class="font-syne text-4xl font-extrabold text-stone-900 mt-2">SESSION DETAILS</h1>
            <p class="text-stone-500 text-xs mt-1">Reference: <span class="font-mono font-bold">{{ $booking->reference_number }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            @if($booking->status === 'approved')
                <span class="px-3.5 py-1.5 bg-emerald-50 text-emerald-805 text-xs font-bold uppercase tracking-wider rounded-full border border-emerald-200 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Approved
                </span>
            @elseif($booking->status === 'declined')
                <span class="px-3.5 py-1.5 bg-rose-50 text-rose-805 text-xs font-bold uppercase tracking-wider rounded-full border border-rose-200 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Declined
                </span>
            @elseif($booking->status === 'canceled')
                <span class="px-3.5 py-1.5 bg-stone-100 text-stone-600 text-xs font-bold uppercase tracking-wider rounded-full border border-stone-205 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span> Canceled
                </span>
            @elseif($booking->status === 'verification_pending')
                <span class="px-3.5 py-1.5 bg-amber-50 text-amber-805 text-xs font-bold uppercase tracking-wider rounded-full border border-amber-200 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Verification Pending
                </span>
            @else
                <span class="px-3.5 py-1.5 bg-blue-50 text-blue-805 text-xs font-bold uppercase tracking-wider rounded-full border border-blue-200 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Pending Review
                </span>
            @endif
            
            <a href="{{ route('bookings.lookup.form') }}" class="text-xs font-bold uppercase tracking-wider text-stone-500 hover:text-stone-900 border border-stone-200 px-4 py-2 rounded-lg transition-colors bg-white shadow-sm flex items-center gap-1.5">
                <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Exit Portal
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 p-6 rounded-xl flex items-start gap-3">
            <i data-lucide="check" class="w-6 h-6 text-emerald-600 flex-shrink-0 mt-0.5 bg-emerald-100 p-1 rounded-full"></i>
            <div class="space-y-1">
                <h4 class="font-bold text-sm">Success</h4>
                <p class="text-xs text-emerald-700 leading-relaxed">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 bg-rose-50 border border-rose-200 text-rose-800 p-6 rounded-xl flex items-start gap-3">
            <i data-lucide="alert-circle" class="w-6 h-6 text-rose-600 flex-shrink-0 mt-0.5 bg-rose-105 p-1 rounded-full"></i>
            <div class="space-y-1">
                <h4 class="font-bold text-sm">Error</h4>
                <p class="text-xs text-rose-705 leading-relaxed">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- View details / messages card -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white border border-stone-200 rounded-xl p-6 space-y-6 shadow-sm">
                <h3 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider">Session Info</h3>
                
                <div class="space-y-4 text-xs">
                    <div>
                        <span class="block text-stone-400 font-medium mb-1">Session Category</span>
                        <span class="font-semibold text-stone-900">{{ $booking->session_type }}</span>
                    </div>
                    <div>
                        <span class="block text-stone-400 font-medium mb-1">Preferred Date</span>
                        <span class="font-semibold text-stone-900">{{ $booking->booking_date->format('F d, Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-stone-400 font-medium mb-1">Time Slot</span>
                        <span class="font-semibold text-stone-900">
                            {{ $booking->timeSlot ? $booking->timeSlot->name . ' (' . substr($booking->timeSlot->start_time, 0, 5) . ' - ' . substr($booking->timeSlot->end_time, 0, 5) . ')' : 'N/A' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-stone-400 font-medium mb-1">Client Name</span>
                        <span class="font-semibold text-stone-900">{{ $booking->name }}</span>
                    </div>
                    <div>
                        <span class="block text-stone-400 font-medium mb-1">Email Address</span>
                        <span class="font-semibold text-stone-900">{{ $booking->email }}</span>
                    </div>
                    <div>
                        <span class="block text-stone-400 font-medium mb-1">Phone Number</span>
                        <span class="font-semibold text-stone-900">{{ $booking->phone ?? 'N/A' }}</span>
                    </div>
                    @if($booking->message)
                        <div>
                            <span class="block text-stone-400 font-medium mb-1">Notes / Inquiries</span>
                            <p class="text-stone-600 bg-stone-50 p-3 rounded-lg border border-stone-200 leading-relaxed font-light whitespace-pre-wrap">{{ $booking->message }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cancel Button for Pending bookings -->
            @if(in_array($booking->status, ['pending', 'verification_pending']) && !$booking->trashed())
                <div class="bg-rose-50 border border-rose-100 rounded-xl p-6 space-y-4 shadow-sm">
                    <h4 class="font-syne text-xs uppercase font-bold text-rose-800 tracking-wider">Cancel Reservation</h4>
                    <p class="text-[11px] text-rose-700 leading-relaxed font-light">
                        Canceling this booking request is permanent. An email confirmation of cancellation will be sent.
                    </p>
                    <form action="{{ route('bookings.cancel', $booking->reference_number) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking request?');">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="trash-2" class="w-4 h-4"></i> Cancel Booking
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Edit Form / Status Message -->
        <div class="lg:col-span-8 space-y-6">
            @if($booking->admin_response)
                <div class="bg-amber-50/40 border border-amber-200/60 p-6 rounded-2xl shadow-sm space-y-3 animate-fade-in">
                    <div class="flex items-center gap-2">
                        <i data-lucide="message-square" class="w-5 h-5 text-amber-600"></i>
                        <h3 class="font-syne font-bold text-amber-900 text-xs uppercase tracking-wider">Photographer's Response</h3>
                    </div>
                    <p class="text-xs text-stone-850 leading-relaxed whitespace-pre-wrap font-light">{{ $booking->admin_response }}</p>
                </div>
            @endif

            <div class="bg-white border border-stone-200 p-8 rounded-2xl shadow-sm">
            @if(!in_array($booking->status, ['pending', 'verification_pending']) || $booking->trashed())
                <div class="py-12 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full bg-stone-50 border border-stone-200 flex items-center justify-center mx-auto text-stone-450">
                        @if($booking->status === 'approved')
                            <i data-lucide="check" class="w-8 h-8 text-emerald-600"></i>
                        @elseif($booking->status === 'declined')
                            <i data-lucide="x" class="w-8 h-8 text-rose-600"></i>
                        @else
                            <i data-lucide="archive" class="w-8 h-8 text-stone-500"></i>
                        @endif
                    </div>
                    
                    <div class="max-w-md mx-auto space-y-2">
                        <h2 class="font-syne text-xl font-bold text-stone-900">
                            @if($booking->status === 'approved')
                                Booking is Approved!
                            @elseif($booking->status === 'declined')
                                Booking was Declined
                            @else
                                Booking is Canceled
                            @endif
                        </h2>
                        <p class="text-xs text-stone-500 leading-relaxed font-light">
                            @if($booking->status === 'approved')
                                Your photography session has been approved. The photographer will contact you with specific package agreements and prep logs soon. Details can no longer be modified.
                            @elseif($booking->status === 'declined')
                                We regret to inform you that we could not accommodate this booking request. You are welcome to submit a new request on our bookings page.
                            @else
                                This booking has been canceled and archived. You can no longer edit or update this request.
                            @endif
                        </p>
                        @if($booking->status !== 'approved')
                            <div class="pt-4">
                                <a href="{{ route('bookings.index') }}" class="inline-block py-2.5 px-6 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors">
                                    Book Another Session
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Edit Booking Form -->
                <div class="space-y-6">
                    <div class="space-y-1">
                        <h2 class="font-syne text-xl font-bold text-stone-900">EDIT BOOKING REQUEST</h2>
                        <p class="text-xs text-stone-500 font-light">Modify your requested session details. Any changes will save instantly.</p>
                    </div>

                    <form action="{{ route('bookings.update', $booking->reference_number) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Your Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $booking->name) }}" required
                                    class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all @error('name') border-rose-500 @enderror">
                                @error('name')
                                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $booking->email) }}" required
                                    class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all @error('email') border-rose-500 @enderror">
                                @error('email')
                                    <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Phone Number</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $booking->phone) }}"
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
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Date -->
                            <div>
                                <label for="booking_date" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Preferred Date</label>
                                <input type="date" id="booking_date" name="booking_date" value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}" required
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

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-xs font-bold uppercase tracking-wider text-stone-700 mb-2">Inquiry / Session Notes</label>
                            <textarea id="message" name="message" rows="5"
                                class="w-full px-4 py-3 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-sm focus:outline-none focus:border-stone-400 focus:bg-white transition-all resize-none"
                                placeholder="Share details...">{{ old('message', $booking->message) }}</textarea>
                            @error('message')
                                <p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-3.5 px-6 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save Booking Updates
                        </button>
                    </form>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>

@if(in_array($booking->status, ['pending', 'verification_pending']) && !$booking->trashed())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const blockedDates = @json($blockedDates ?? []);
        const dateInput = document.getElementById('booking_date');
        const initialDate = "{{ $booking->booking_date->format('Y-m-d') }}";
        
        // Prevent selection of blocked dates, unless it is the currently selected date of this booking
        dateInput.addEventListener('input', function() {
            const selectedDate = this.value;
            if (selectedDate !== initialDate && blockedDates.includes(selectedDate)) {
                alert('The photographer is unavailable on this date. Please select a different date.');
                this.value = initialDate;
            }
        });
        
        // Disable selecting past dates
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    });
</script>
@endif
@endsection
