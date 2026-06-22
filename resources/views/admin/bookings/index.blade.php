@extends('layouts.admin')

@section('title', 'Manage Bookings')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4 text-emerald-600 bg-emerald-100 p-0.5 rounded-full"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 bg-rose-100 p-0.5 rounded-full"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Header with Filters & Action Buttons -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 border-b border-stone-200 pb-6">
        <div class="space-y-1">
            <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 text-2xl">
                <i data-lucide="calendar" class="w-6 h-6 text-stone-500"></i> Booking Requests
            </h2>
            <p class="text-xs text-stone-500 font-light">Approve, decline, and visualize photo session reservations.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <!-- Navigation buttons -->
            <a href="{{ route('admin.bookings.create') }}" class="px-4 py-2 bg-stone-950 hover:bg-stone-850 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-colors shadow-sm flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Booking
            </a>
            <a href="{{ route('admin.bookings.calendar') }}" class="px-4 py-2 bg-white border border-stone-200 hover:border-stone-300 text-stone-700 hover:text-stone-900 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors shadow-sm flex items-center gap-1.5">
                <i data-lucide="calendar-days" class="w-4 h-4"></i> Calendar View
            </a>
            <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-white border border-stone-200 hover:border-stone-300 text-stone-700 hover:text-stone-900 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors shadow-sm flex items-center gap-1.5">
                <i data-lucide="settings" class="w-4 h-4"></i> Booking Settings
            </a>

            <!-- Filter Tabs -->
            <div class="flex items-center gap-1 bg-stone-100 p-1 rounded-lg border border-stone-200">
                <a href="{{ route('admin.bookings.index') }}" class="px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded {{ !$status ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-650 hover:text-stone-900' }}">
                    All
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded {{ $status === 'pending' ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-650 hover:text-stone-900' }}">
                    Pending
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'approved']) }}" class="px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded {{ $status === 'approved' ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-650 hover:text-stone-900' }}">
                    Approved
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'declined']) }}" class="px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded {{ $status === 'declined' ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-650 hover:text-stone-900' }}">
                    Declined
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'verification_pending']) }}" class="px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded {{ $status === 'verification_pending' ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-650 hover:text-stone-900' }}">
                    Unverified
                </a>
                <a href="{{ route('admin.bookings.index', ['status' => 'canceled']) }}" class="px-2.5 py-1.5 text-[10px] font-bold uppercase tracking-wider rounded {{ $status === 'canceled' ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-650 hover:text-stone-900' }}">
                    Canceled
                </a>
            </div>
        </div>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white border border-stone-200 rounded-xl p-12 text-center text-stone-400 text-sm shadow-sm">
            No booking requests found for the selected status.
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm hover:border-stone-300 transition-colors animate-fade-in relative overflow-hidden">
                    
                    @if($booking->status !== 'approved' && $booking->status !== 'canceled' && $booking->hasConflict())
                        <div class="absolute top-0 right-0 left-0 bg-amber-500/10 border-b border-amber-500/20 text-amber-800 text-[10px] font-bold uppercase tracking-wider py-1 px-6 flex items-center gap-1.5">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-650"></i>
                            <span>Conflict Alert: Approving this slot will exceed the capacity limit ({{ $booking->timeSlot ? $booking->timeSlot->capacity : 1 }})</span>
                        </div>
                    @endif

                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6 {{ ($booking->status !== 'approved' && $booking->status !== 'canceled' && $booking->hasConflict()) ? 'pt-4' : '' }}">
                        <!-- Details -->
                        <div class="space-y-3 flex-grow min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="font-bold text-base text-stone-900">{{ $booking->name }}</h3>
                                
                                <span class="text-xs text-stone-400 font-mono bg-stone-50 px-2 py-0.5 border border-stone-200 rounded">
                                    {{ $booking->reference_number }}
                                </span>

                                <!-- Status Badge -->
                                @if($booking->status === 'verification_pending')
                                    <span class="text-[9px] px-2.5 py-0.5 bg-amber-50 text-amber-850 font-bold uppercase rounded border border-amber-200">
                                        Unverified
                                    </span>
                                @elseif($booking->status === 'pending')
                                    <span class="text-[9px] px-2.5 py-0.5 bg-blue-50 text-blue-800 font-bold uppercase rounded border border-blue-200">
                                        Pending Review
                                    </span>
                                @elseif($booking->status === 'approved')
                                    <span class="text-[9px] px-2.5 py-0.5 bg-emerald-50 text-emerald-800 font-bold uppercase rounded border border-emerald-200">
                                        Approved
                                    </span>
                                @elseif($booking->status === 'declined')
                                    <span class="text-[9px] px-2.5 py-0.5 bg-rose-50 text-rose-800 font-bold uppercase rounded border border-rose-200">
                                        Declined
                                    </span>
                                @elseif($booking->status === 'canceled' || $booking->trashed())
                                    <span class="text-[9px] px-2.5 py-0.5 bg-stone-100 text-stone-605 font-bold uppercase rounded border border-stone-200">
                                        Canceled / Archived
                                    </span>
                                @endif

                                <span class="text-xs text-stone-400 font-medium">
                                    {{ $booking->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 bg-stone-50 border border-stone-150 p-4 rounded-lg text-xs text-stone-700">
                                <div>
                                    <span class="block text-stone-400 font-medium mb-1">Session Category</span>
                                    <span class="font-bold text-stone-900">{{ $booking->session_type }}</span>
                                </div>
                                <div>
                                    <span class="block text-stone-400 font-medium mb-1">Requested Date</span>
                                    <span class="font-bold text-stone-900 flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-stone-450"></i>
                                        {{ $booking->booking_date->format('M d, Y') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-stone-400 font-medium mb-1">Time Slot</span>
                                    <span class="font-bold text-stone-900 flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-stone-450"></i>
                                        {{ $booking->timeSlot ? $booking->timeSlot->name . ' (' . substr($booking->timeSlot->start_time, 0, 5) . ' - ' . substr($booking->timeSlot->end_time, 0, 5) . ')' : 'N/A' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-stone-400 font-medium mb-1">Contact Details</span>
                                    <span class="font-semibold text-stone-900 block truncate">{{ $booking->email }}</span>
                                    @if($booking->phone)
                                        <span class="font-semibold text-stone-900 block truncate mt-0.5">{{ $booking->phone }}</span>
                                    @endif
                                </div>
                            </div>

                            @if($booking->message)
                                <div class="text-xs text-stone-750 bg-stone-50/50 border border-stone-200 p-4 rounded-lg whitespace-pre-line leading-relaxed font-light">
                                    <span class="block font-bold text-stone-400 uppercase tracking-wider text-[9px] mb-2 font-syne">Message Notes</span>
                                    {{ $booking->message }}
                                </div>
                            @endif
                        </div>

                        <!-- Actions Buttons -->
                        <div class="flex flex-row lg:flex-col items-center gap-2 self-end lg:self-start flex-shrink-0">
                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="w-full flex items-center justify-center gap-1.5 px-4 py-2 border border-stone-250 text-stone-750 hover:bg-stone-50 text-xs font-bold uppercase tracking-wider rounded-lg transition-colors">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit / Respond
                            </a>

                            @if($booking->status !== 'approved' && $booking->status !== 'canceled')
                                <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" 
                                        @if($booking->hasConflict()) disabled title="Capacity Conflict" @endif
                                        class="w-full flex items-center justify-center gap-1.5 px-4 py-2 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-colors shadow-sm {{ $booking->hasConflict() ? 'bg-stone-300 cursor-not-allowed' : 'bg-stone-950 hover:bg-stone-850' }}">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i> Approve
                                    </button>
                                </form>
                            @endif

                            @if($booking->status === 'canceled' || $booking->trashed())
                                <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-4 py-2 border border-stone-300 hover:border-stone-400 text-stone-700 hover:bg-stone-50 text-xs font-bold uppercase tracking-wider rounded-lg transition-colors shadow-sm">
                                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Restore
                                    </button>
                                </form>
                            @endif

                            @if($booking->status !== 'declined' && $booking->status !== 'canceled')
                                <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="declined">
                                    <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-4 py-2 border border-stone-250 text-rose-700 hover:bg-rose-50 text-xs font-bold uppercase tracking-wider rounded-lg transition-colors">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i> Decline
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel and archive this booking request?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-4 py-2 text-stone-550 hover:text-rose-700 text-xs font-bold uppercase tracking-wider hover:bg-stone-50 rounded-lg transition-colors">
                                    <i data-lucide="archive" class="w-3.5 h-3.5"></i> Archive
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
