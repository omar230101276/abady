@extends('layouts.admin')

@section('title', 'Admin Calendar View')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="border-b border-stone-200 pb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-1">
            <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 text-2xl">
                <i data-lucide="calendar" class="w-6 h-6 text-stone-500"></i> Bookings Calendar
            </h2>
            <p class="text-xs text-stone-500 font-light">Visual schedule of approved, pending, and blocked dates.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 border border-stone-200 text-stone-605 hover:text-stone-900 text-xs font-bold uppercase tracking-wider hover:bg-stone-50 rounded-lg transition-colors flex items-center gap-1.5 shadow-sm bg-white">
                <i data-lucide="list" class="w-3.5 h-3.5"></i> List View
            </a>
            <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 border border-stone-200 text-stone-605 hover:text-stone-900 text-xs font-bold uppercase tracking-wider hover:bg-stone-50 rounded-lg transition-colors flex items-center gap-1.5 shadow-sm bg-white">
                <i data-lucide="settings" class="w-3.5 h-3.5"></i> Settings
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-805 p-4 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="check" class="w-4 h-4 text-emerald-600 bg-emerald-100 p-0.5 rounded-full"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-805 p-4 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-605 bg-rose-100 p-0.5 rounded-full"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Calendar Grid -->
        <div class="lg:col-span-8 bg-white border border-stone-200 rounded-xl p-6 shadow-sm space-y-6">
            <!-- Calendar Navigation Header -->
            <div class="flex items-center justify-between">
                <h3 class="font-syne font-extrabold text-stone-900 text-lg uppercase tracking-wider">
                    {{ $currentDate->format('F Y') }}
                </h3>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.bookings.calendar', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" 
                       class="p-2 border border-stone-200 hover:border-stone-300 text-stone-600 hover:text-stone-900 rounded-lg transition-all shadow-sm bg-white">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </a>
                    <a href="{{ route('admin.bookings.calendar', ['month' => date('n'), 'year' => date('Y')]) }}" 
                       class="px-3 py-1.5 border border-stone-200 hover:border-stone-300 text-stone-600 hover:text-stone-900 rounded-lg text-xs font-bold uppercase tracking-wider transition-all shadow-sm bg-white">
                        Today
                    </a>
                    <a href="{{ route('admin.bookings.calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" 
                       class="p-2 border border-stone-200 hover:border-stone-300 text-stone-600 hover:text-stone-900 rounded-lg transition-all shadow-sm bg-white">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Month Grid -->
            <div>
                <!-- Day Names -->
                <div class="grid grid-cols-7 text-center border-b border-stone-105 pb-3">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                        <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">{{ $dayName }}</span>
                    @endforeach
                </div>

                @php
                    $daysInMonth = $currentDate->daysInMonth;
                    $startOfWeek = $currentDate->copy()->startOfMonth()->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
                    $totalCells = ceil(($daysInMonth + $startOfWeek) / 7) * 7;
                @endphp

                <!-- Days cells -->
                <div class="grid grid-cols-7 gap-px bg-stone-200 mt-2 rounded-lg overflow-hidden border border-stone-200">
                    @for($i = 0; $i < $totalCells; $i++)
                        @php
                            $day = $i - $startOfWeek + 1;
                            $isValidDay = ($day > 0 && $day <= $daysInMonth);
                            $dateStr = $isValidDay ? sprintf('%04d-%02d-%02d', $year, $month, $day) : null;
                            $dayBookings = $isValidDay ? $bookingsByDate->get($dateStr, collect()) : collect();
                            $blocked = $isValidDay ? $blockedDatesByDate->get($dateStr) : null;
                            $isToday = $isValidDay && ($dateStr === date('Y-m-d'));
                        @endphp

                        @if($isValidDay)
                            <div class="bg-white min-h-[105px] p-2 space-y-2 flex flex-col justify-between transition-colors hover:bg-stone-50/40 relative {{ $blocked ? 'bg-rose-50/30' : '' }}">
                                <!-- Day number -->
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold {{ $isToday ? 'w-5 h-5 rounded-full bg-stone-900 text-white flex items-center justify-center font-bold' : 'text-stone-700' }}">
                                        {{ $day }}
                                    </span>
                                    
                                    @if($blocked)
                                        <span class="text-[8px] px-1.5 py-0.5 bg-rose-50 text-rose-800 rounded font-bold uppercase border border-rose-100 flex items-center gap-0.5" title="Blocked: {{ $blocked->reason }}">
                                            <i data-lucide="lock" class="w-2.5 h-2.5"></i> Blocked
                                        </span>
                                    @endif
                                </div>

                                <!-- Bookings indicators -->
                                <div class="flex-grow space-y-1">
                                    @foreach($dayBookings as $booking)
                                        @php
                                            $colorClass = 'bg-stone-100 text-stone-750 border-stone-200';
                                            if($booking->status === 'approved') {
                                                $colorClass = 'bg-emerald-50 text-emerald-805 border-emerald-150';
                                            } elseif($booking->status === 'declined') {
                                                $colorClass = 'bg-rose-50 text-rose-805 border-rose-150';
                                            } elseif($booking->status === 'canceled' || $booking->trashed()) {
                                                $colorClass = 'bg-stone-100 text-stone-500 border-stone-200 line-through';
                                            } elseif($booking->status === 'verification_pending') {
                                                $colorClass = 'bg-amber-50 text-amber-805 border-amber-155';
                                            } elseif($booking->status === 'pending') {
                                                $colorClass = 'bg-blue-50 text-blue-805 border-blue-150';
                                            }
                                        @endphp
                                        <div class="px-1.5 py-1 text-[9px] font-bold rounded border truncate transition-all cursor-pointer {{ $colorClass }}" 
                                             title="{{ $booking->name }} - {{ $booking->session_type }} ({{ $booking->timeSlot ? $booking->timeSlot->name : 'N/A' }})"
                                             onclick="window.location.href='{{ route('admin.bookings.index') }}?status={{ $booking->status }}'">
                                            {{ substr($booking->name, 0, 10) }}.. ({{ $booking->timeSlot ? substr($booking->timeSlot->name, 0, 4) : '' }})
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="bg-stone-50 min-h-[105px]"></div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>

        <!-- Sidebar (Blocked Dates Panel) -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Block Date Form -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider flex items-center gap-2 border-b border-stone-100 pb-3">
                    <i data-lucide="ban" class="w-4 h-4 text-stone-400"></i> Block Date Availability
                </h3>
                <p class="text-[10px] text-stone-500 leading-relaxed font-light">Prevent clients from submitting booking requests on specific dates (e.g., photographer vacation, major campaigns).</p>
                
                <form action="{{ route('admin.bookings.block-date') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="blocked_date" class="block text-[10px] font-bold uppercase tracking-wider text-stone-750 mb-1.5">Select Date</label>
                        <input type="date" id="blocked_date" name="blocked_date" required min="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
                    </div>
                    <div>
                        <label for="reason" class="block text-[10px] font-bold uppercase tracking-wider text-stone-750 mb-1.5">Reason (Optional)</label>
                        <input type="text" id="reason" name="reason" placeholder="e.g. Travel Campaign"
                            class="w-full px-3 py-2 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                    </div>
                    <button type="submit" class="w-full py-2.5 px-4 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors shadow-sm">
                        Block Selected Date
                    </button>
                </form>
            </div>

            <!-- Blocked Dates List -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm space-y-4">
                <h3 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider flex items-center gap-2 border-b border-stone-100 pb-3">
                    <i data-lucide="lock" class="w-4 h-4 text-stone-400"></i> Blocked Date Records
                </h3>

                @if($allBlockedDates->isEmpty())
                    <p class="text-xs text-stone-405 font-light text-center py-4">No blocked dates set.</p>
                @else
                    <div class="divide-y divide-stone-100 max-h-[300px] overflow-y-auto pr-2">
                        @foreach($allBlockedDates as $bd)
                            <div class="py-2.5 flex items-center justify-between text-xs gap-4 first:pt-0 last:pb-0">
                                <div>
                                    <span class="font-bold text-stone-800">{{ $bd->blocked_date->format('M d, Y') }}</span>
                                    @if($bd->reason)
                                        <span class="block text-[10px] text-stone-400 font-light truncate max-w-[150px]">{{ $bd->reason }}</span>
                                    @endif
                                </div>
                                <form action="{{ route('admin.bookings.unblock-date', $bd->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove the block from this date?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-stone-450 hover:text-rose-700 p-1 rounded hover:bg-stone-50 transition-colors">
                                        <i data-lucide="unlock" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
