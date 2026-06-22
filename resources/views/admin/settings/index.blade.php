@extends('layouts.admin')

@section('title', 'Booking Settings')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="border-b border-stone-200 pb-6 flex items-center justify-between">
        <div class="space-y-1">
            <h2 class="font-syne font-bold text-stone-900 flex items-center gap-2 text-2xl">
                <i data-lucide="sliders" class="w-6 h-6 text-stone-500"></i> Booking Settings
            </h2>
            <p class="text-xs text-stone-500 font-light font-medium">Configure session capacity slots, notification toggles, and view message dispatches.</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 border border-stone-200 text-stone-605 hover:text-stone-900 text-xs font-bold uppercase tracking-wider hover:bg-stone-50 rounded-lg transition-colors flex items-center gap-1.5 shadow-sm bg-white">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Requests
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-805 p-4 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="check" class="w-4 h-4 text-emerald-600 bg-emerald-100 p-0.5 rounded-full"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Settings Panel (Toggles & New Time Slot) -->
        <div class="lg:col-span-5 space-y-8">
            <!-- Global Config -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm space-y-6">
                <h3 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider flex items-center gap-2 border-b border-stone-100 pb-3">
                    <i data-lucide="toggle-left" class="w-4 h-4 text-stone-400"></i> Notification Control
                </h3>
                
                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="booking_notifications_enabled" name="booking_notifications_enabled" 
                            value="1" {{ $notificationsEnabled ? 'checked' : '' }}
                            class="w-4 h-4 text-stone-900 border-stone-300 rounded focus:ring-stone-900 mt-1 cursor-pointer">
                        <div>
                            <label for="booking_notifications_enabled" class="text-xs font-bold uppercase tracking-wider text-stone-750 cursor-pointer">Enable Email Notifications</label>
                            <p class="text-[10px] text-stone-500 leading-relaxed font-light mt-0.5">
                                Toggle automated confirmation, approval, decline, and verification emails for clients and photographer.
                            </p>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors shadow-sm">
                        Save Preferences
                    </button>
                </form>
            </div>

            <!-- Create Time Slot -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm space-y-6">
                <h3 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider flex items-center gap-2 border-b border-stone-100 pb-3">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-stone-400"></i> Add Predefined Slot
                </h3>
                
                <form action="{{ route('admin.time-slots.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-[10px] font-bold uppercase tracking-wider text-stone-750 mb-1.5">Slot Label</label>
                        <input type="text" id="name" name="name" required placeholder="e.g. Afternoon Session"
                            class="w-full px-3 py-2 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-[10px] font-bold uppercase tracking-wider text-stone-750 mb-1.5">Start Time</label>
                            <input type="time" id="start_time" name="start_time" required
                                class="w-full px-3 py-2 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
                        </div>
                        <div>
                            <label for="end_time" class="block text-[10px] font-bold uppercase tracking-wider text-stone-750 mb-1.5">End Time</label>
                            <input type="time" id="end_time" name="end_time" required
                                class="w-full px-3 py-2 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="capacity" class="block text-[10px] font-bold uppercase tracking-wider text-stone-750 mb-1.5">Capacity (Max Bookings)</label>
                            <input type="number" id="capacity" name="capacity" value="1" min="1" required
                                class="w-full px-3 py-2 bg-stone-50 border border-stone-200 text-stone-900 rounded-lg text-xs focus:outline-none focus:border-stone-400 focus:bg-white transition-all">
                        </div>
                        <div class="flex items-center gap-2 pt-6">
                            <input type="checkbox" id="is_active" name="is_active" value="1" checked
                                class="w-4 h-4 text-stone-900 border-stone-300 rounded focus:ring-stone-900 cursor-pointer">
                            <label for="is_active" class="text-[10px] font-bold uppercase tracking-wider text-stone-750 cursor-pointer">Active</label>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-stone-950 hover:bg-stone-850 text-white font-bold uppercase tracking-wider text-xs rounded-lg transition-colors shadow-sm">
                        Create Time Slot
                    </button>
                </form>
            </div>
        </div>

        <!-- Predefined Slots list & Audit Trail -->
        <div class="lg:col-span-7 space-y-8">
            <!-- Active Time Slots -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm space-y-6">
                <h3 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider flex items-center gap-2 border-b border-stone-100 pb-3">
                    <i data-lucide="clock" class="w-4 h-4 text-stone-400"></i> Predefined Time Slots
                </h3>

                @if($timeSlots->isEmpty())
                    <p class="text-xs text-stone-405 text-center py-6 font-light">No time slots configured. Clients won't be able to book sessions.</p>
                @else
                    <div class="divide-y divide-stone-100 max-h-[300px] overflow-y-auto pr-2">
                        @foreach($timeSlots as $slot)
                            <div class="py-3 flex items-center justify-between text-xs gap-4 first:pt-0 last:pb-0">
                                <div>
                                    <span class="font-bold text-stone-900 block">{{ $slot->name }}</span>
                                    <span class="text-stone-500 font-light text-[10px]">
                                        {{ substr($slot->start_time, 0, 5) }} - {{ substr($slot->end_time, 0, 5) }}
                                        &bull; Capacity: <span class="font-bold text-stone-700">{{ $slot->capacity }}</span>
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($slot->is_active)
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 text-[9px] font-bold uppercase rounded border border-emerald-150">Active</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-stone-100 text-stone-500 text-[9px] font-bold uppercase rounded border border-stone-200">Inactive</span>
                                    @endif

                                    <form action="{{ route('admin.time-slots.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this time slot? This can affect historical bookings referencing this slot.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-stone-400 hover:text-rose-700 p-1 rounded hover:bg-stone-50 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Email Notification Logs -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm space-y-6">
                <h3 class="font-syne text-xs uppercase font-bold text-stone-750 tracking-wider flex items-center gap-2 border-b border-stone-100 pb-3">
                    <i data-lucide="scroll" class="w-4 h-4 text-stone-400"></i> Dispatch Audit Ledger
                </h3>

                @if($notificationLogs->isEmpty())
                    <p class="text-xs text-stone-405 text-center py-6 font-light">No dispatch logs recorded yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-stone-200 text-stone-400">
                                    <th class="py-2.5 font-bold uppercase tracking-wider text-[9px]">Recipient</th>
                                    <th class="py-2.5 font-bold uppercase tracking-wider text-[9px]">Notification</th>
                                    <th class="py-2.5 font-bold uppercase tracking-wider text-[9px]">Status</th>
                                    <th class="py-2.5 font-bold uppercase tracking-wider text-[9px]">Dispatched</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-stone-700">
                                @foreach($notificationLogs as $log)
                                    <tr class="align-top hover:bg-stone-50/50 transition-colors">
                                        <td class="py-3 pr-2">
                                            <span class="font-medium block truncate max-w-[150px]" title="{{ $log->recipient_email }}">{{ $log->recipient_email }}</span>
                                            @if($log->booking)
                                                <span class="text-[9px] font-mono text-stone-400">Ref: {{ $log->booking->reference_number }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 pr-2">
                                            <span class="font-semibold text-stone-900 block truncate max-w-[150px]" title="{{ $log->notification_type }}">
                                                {{ preg_replace('/(?<!\ )[A-Z]/', ' $0', $log->notification_type) }}
                                            </span>
                                            <span class="text-[9px] text-stone-400 font-light block uppercase">{{ $log->channel }}</span>
                                        </td>
                                        <td class="py-3 pr-2">
                                            @if($log->status === 'sent')
                                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 text-[9px] font-bold uppercase rounded border border-emerald-200 inline-block">Sent</span>
                                            @elseif($log->status === 'pending')
                                                <span class="px-2 py-0.5 bg-amber-50 text-amber-800 text-[9px] font-bold uppercase rounded border border-amber-200 inline-block animate-pulse">Pending</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-rose-50 text-rose-800 text-[9px] font-bold uppercase rounded border border-rose-200 inline-block" title="{{ $log->error_message }}">Failed</span>
                                                <span class="block text-[8px] text-rose-600 truncate max-w-[120px] font-mono font-light mt-1" title="{{ $log->error_message }}">{{ $log->error_message }}</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-stone-505 font-light text-[10px]">
                                            {{ $log->created_at->format('M d, H:i') }}
                                            <span class="block text-[9px] text-stone-400 font-light">{{ $log->created_at->diffForHumans() }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Log Pagination -->
                    <div class="pt-4 border-t border-stone-105">
                        {{ $notificationLogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
