@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Card 1: Albums -->
        <div class="bg-white border border-stone-200 p-6 rounded-xl flex items-center justify-between shadow-sm">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Total Albums</span>
                <h3 class="text-3xl font-bold mt-2 text-stone-900">{{ $stats['albums'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-700 flex items-center justify-center rounded-lg border border-amber-100">
                <i data-lucide="image" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 2: Photos -->
        <div class="bg-white border border-stone-200 p-6 rounded-xl flex items-center justify-between shadow-sm">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Total Photos</span>
                <h3 class="text-3xl font-bold mt-2 text-stone-900">{{ $stats['photos'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-700 flex items-center justify-center rounded-lg border border-blue-100">
                <i data-lucide="images" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 3: Videos -->
        <div class="bg-white border border-stone-200 p-6 rounded-xl flex items-center justify-between shadow-sm">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Total Videos</span>
                <h3 class="text-3xl font-bold mt-2 text-stone-900">{{ $stats['videos'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-700 flex items-center justify-center rounded-lg border border-indigo-100">
                <i data-lucide="video" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 4: Messages -->
        <div class="bg-white border border-stone-200 p-6 rounded-xl flex items-center justify-between shadow-sm">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Unread Messages</span>
                <h3 class="text-3xl font-bold mt-2 text-stone-900">{{ $stats['contacts'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-700 flex items-center justify-center rounded-lg border border-emerald-100">
                <i data-lucide="mail" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 5: Bookings -->
        <div class="bg-white border border-stone-200 p-6 rounded-xl flex items-center justify-between shadow-sm">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Pending Bookings</span>
                <h3 class="text-3xl font-bold mt-2 text-stone-900">{{ $stats['pending_bookings'] }}</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-700 flex items-center justify-center rounded-lg border border-amber-100">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Secondary Dashboard Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Inquiries -->
        <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2">
                    <i data-lucide="mail-open" class="w-5 h-5 text-stone-500"></i> Recent Inquiries
                </h2>
                <a href="{{ route('admin.contacts.index') }}" class="text-xs font-bold text-amber-700 hover:text-amber-800 uppercase tracking-wider flex items-center gap-1">
                    View All <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            @if($latestContacts->isEmpty())
                <div class="py-12 text-center text-stone-400 text-sm">
                    No contact messages received yet.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($latestContacts as $contact)
                        <div class="p-4 bg-stone-50 border border-stone-200 rounded-lg hover:border-stone-300 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                <div>
                                    <h4 class="font-bold text-xs text-stone-900">{{ $contact->name }}</h4>
                                    <span class="text-[10px] text-stone-400 block mt-0.5">{{ $contact->email }}</span>
                                </div>
                                <span class="text-[10px] text-stone-400 font-medium whitespace-nowrap">{{ $contact->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-[11px] text-stone-700 leading-relaxed bg-white border border-stone-150 p-2 rounded mt-1.5 line-clamp-2">
                                {{ $contact->message }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Bookings -->
        <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-5 h-5 text-stone-500"></i> Recent Bookings
                </h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-amber-700 hover:text-amber-800 uppercase tracking-wider flex items-center gap-1">
                    View All <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            @if($latestBookings->isEmpty())
                <div class="py-12 text-center text-stone-400 text-sm">
                    No booking requests received yet.
                </div>
            @else
                <div class="space-y-4">
                    @foreach($latestBookings as $booking)
                        <div class="p-4 bg-stone-50 border border-stone-200 rounded-lg hover:border-stone-300 transition-colors">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                <div>
                                    <h4 class="font-bold text-xs text-stone-900">{{ $booking->name }}</h4>
                                    <span class="text-[8px] font-mono text-stone-400">Ref: {{ $booking->reference_number }}</span>
                                </div>
                                @if($booking->status === 'verification_pending')
                                    <span class="text-[8px] px-2 py-0.5 bg-amber-50 text-amber-800 font-bold uppercase rounded border border-amber-250">Unverified</span>
                                @elseif($booking->status === 'pending')
                                    <span class="text-[8px] px-2 py-0.5 bg-blue-50 text-blue-800 font-bold uppercase rounded border border-blue-200">Pending</span>
                                @elseif($booking->status === 'approved')
                                    <span class="text-[8px] px-2 py-0.5 bg-emerald-50 text-emerald-800 font-bold uppercase rounded border border-emerald-200">Approved</span>
                                @elseif($booking->status === 'declined')
                                    <span class="text-[8px] px-2 py-0.5 bg-rose-50 text-rose-800 font-bold uppercase rounded border border-rose-200">Declined</span>
                                @elseif($booking->status === 'canceled' || $booking->trashed())
                                    <span class="text-[8px] px-2 py-0.5 bg-stone-100 text-stone-600 font-bold uppercase rounded border border-stone-200">Canceled</span>
                                @endif
                            </div>
                            <div class="text-[10px] text-stone-605 space-y-1 mt-1 bg-white border border-stone-150 p-2 rounded">
                                <p><span class="font-bold text-stone-400">Type:</span> {{ $booking->session_type }}</p>
                                <p><span class="font-bold text-stone-400">Date:</span> {{ $booking->booking_date->format('M d, Y') }}</p>
                                @if($booking->timeSlot)
                                    <p><span class="font-bold text-stone-400">Slot:</span> {{ $booking->timeSlot->name }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Quick Operations -->
        <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm">
            <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 mb-6">
                <i data-lucide="activity" class="w-5 h-5 text-stone-500"></i> Quick Operations
            </h2>

            <div class="flex flex-col gap-3">
                <a href="{{ route('admin.albums.index') }}" class="flex items-center gap-3 p-3.5 text-sm font-semibold border border-stone-200 rounded-lg hover:bg-stone-50 hover:border-stone-300 transition-all text-stone-850">
                    <i data-lucide="folder-plus" class="w-5 h-5 text-amber-600"></i>
                    <span>Create & Edit Albums</span>
                </a>
                <a href="{{ route('admin.videos.index') }}" class="flex items-center gap-3 p-3.5 text-sm font-semibold border border-stone-200 rounded-lg hover:bg-stone-50 hover:border-stone-300 transition-all text-stone-850">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-indigo-650"></i>
                    <span>Add Videos or MP4s</span>
                </a>
                <a href="{{ route('admin.collaborations.index') }}" class="flex items-center gap-3 p-3.5 text-sm font-semibold border border-stone-200 rounded-lg hover:bg-stone-50 hover:border-stone-300 transition-all text-stone-850">
                    <i data-lucide="user-plus" class="w-5 h-5 text-emerald-600"></i>
                    <span>Manage Collaborations</span>
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 p-3.5 text-sm font-semibold border border-stone-200 rounded-lg hover:bg-stone-50 hover:border-stone-300 transition-all text-stone-850">
                    <i data-lucide="calendar" class="w-5 h-5 text-amber-600"></i>
                    <span>Review Bookings</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
