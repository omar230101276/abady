<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BlockedDate;
use App\Models\Setting;
use App\Models\TimeSlot;
use App\Notifications\BookingStatusChangedNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        
        $query = Booking::withTrashed()->with('timeSlot');

        if ($status) {
            if ($status === 'canceled') {
                // soft deleted are canceled
                $query->whereNotNull('deleted_at');
            } elseif ($status === 'pending') {
                $query->where('status', 'pending')->whereNull('deleted_at');
            } elseif ($status === 'verification_pending') {
                $query->where('status', 'verification_pending')->whereNull('deleted_at');
            } else {
                $query->where('status', $status)->whereNull('deleted_at');
            }
        } else {
            // Default to showing all non-deleted bookings or all bookings?
            // Let's show all bookings including soft-deleted ones but sorted by creation.
        }

        $bookings = $query->latest()->paginate(15);
        return view('admin.bookings.index', compact('bookings', 'status'));
    }

    /**
     * Update the status of the booking.
     */
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:pending,approved,declined,canceled',
        ]);

        $newStatus = $request->status;

        if ($newStatus === 'approved') {
            if ($booking->hasConflict()) {
                return back()->with('error', 'Conflict Detected: The time slot ' . ($booking->timeSlot ? $booking->timeSlot->name : '') . ' is already at full capacity for this date. Cannot approve booking.');
            }
        }

        // If status is being set to canceled, soft delete it
        if ($newStatus === 'canceled') {
            $booking->update(['status' => 'canceled']);
            if (!$booking->trashed()) {
                $booking->delete();
            }
        } else {
            // Restore if it was soft deleted
            if ($booking->trashed()) {
                $booking->restore();
            }
            $booking->update(['status' => $newStatus]);
        }

        // Send status update notification
        if (Setting::get('booking_notifications_enabled', '1') === '1') {
            $booking->notify(new BookingStatusChangedNotification($booking));
        }

        return back()->with('success', 'Booking status updated to ' . ucfirst($newStatus) . ' successfully.');
    }

    /**
     * Remove the specified booking from storage (Force delete or soft delete).
     * Let's keep soft delete for history, so destroy will soft delete it.
     */
    public function destroy($id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);
        
        $booking->update(['status' => 'canceled']);
        $booking->delete();
        
        return back()->with('success', 'Booking request canceled and archived successfully.');
    }

    /**
     * Display a calendar view of the bookings.
     */
    public function calendar(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $month = $request->query('month', date('n'));

        $currentDate = Carbon::createFromDate($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();

        $bookingsByDate = Booking::withTrashed()
            ->with('timeSlot')
            ->whereMonth('booking_date', $month)
            ->whereYear('booking_date', $year)
            ->get()
            ->groupBy(function($b) {
                return $b->booking_date->format('Y-m-d');
            });

        $blockedDatesByDate = BlockedDate::whereMonth('blocked_date', $month)
            ->whereYear('blocked_date', $year)
            ->get()
            ->keyBy(function($bd) {
                return $bd->blocked_date->format('Y-m-d');
            });

        $allBlockedDates = BlockedDate::orderBy('blocked_date', 'desc')->get();

        return view('admin.bookings.calendar', compact(
            'bookingsByDate',
            'blockedDatesByDate',
            'allBlockedDates',
            'currentDate',
            'prevMonth',
            'nextMonth',
            'year',
            'month'
        ));
    }

    /**
     * Block a specific date.
     */
    public function blockDate(Request $request)
    {
        $request->validate([
            'blocked_date' => 'required|date|unique:blocked_dates,blocked_date',
            'reason' => 'nullable|string|max:255',
        ]);

        BlockedDate::create([
            'blocked_date' => $request->blocked_date,
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Date blocked successfully.');
    }

    /**
     * Unblock a specific date.
     */
    public function unblockDate($id)
    {
        $blockedDate = BlockedDate::findOrFail($id);
        $blockedDate->delete();

        return back()->with('success', 'Date unblocked successfully.');
    }

    /**
     * Show the form for creating a new booking request.
     */
    public function create()
    {
        $timeSlots = TimeSlot::where('is_active', true)->orderBy('start_time')->get();
        $blockedDates = BlockedDate::pluck('blocked_date')->map(fn($d) => $d->format('Y-m-d'))->toArray();
        return view('admin.bookings.create', compact('timeSlots', 'blockedDates'));
    }

    /**
     * Store a manually created booking request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'session_type' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'message' => 'nullable|string',
            'status' => 'required|string|in:pending,approved,declined,canceled',
        ]);

        // Check if date is blocked
        $isBlocked = BlockedDate::whereDate('blocked_date', $request->booking_date)->exists();
        if ($isBlocked) {
            return back()->withErrors(['booking_date' => 'Warning: The selected date is blocked on the calendar. Please select another date.'])->withInput();
        }

        // Generate booking reference
        do {
            $referenceNumber = 'ABD-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Booking::where('reference_number', $referenceNumber)->exists());

        // Check for capacity conflict if approving directly
        if ($request->status === 'approved') {
            $tempBooking = new Booking([
                'booking_date' => $request->booking_date,
                'time_slot_id' => $request->time_slot_id,
            ]);
            if ($tempBooking->hasConflict()) {
                return back()->withErrors(['time_slot_id' => 'Capacity Conflict: The selected slot has already reached its capacity. Choose another slot or set status to Pending.'])->withInput();
            }
        }

        $booking = Booking::create([
            'reference_number' => $referenceNumber,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'session_type' => $request->session_type,
            'booking_date' => $request->booking_date,
            'time_slot_id' => $request->time_slot_id,
            'message' => $request->message,
            'status' => $request->status,
        ]);

        // Send confirmation/approval email if notifications are enabled
        if ($request->status === 'approved' && Setting::get('booking_notifications_enabled', '1') === '1') {
            $booking->notify(new BookingStatusChangedNotification($booking));
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking created manually and ' . ($request->status === 'approved' ? 'approved' : 'pending review') . ' successfully.');
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit($id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);
        $timeSlots = TimeSlot::where('is_active', true)->orderBy('start_time')->get();
        $blockedDates = BlockedDate::pluck('blocked_date')->map(fn($d) => $d->format('Y-m-d'))->toArray();
        return view('admin.bookings.edit', compact('booking', 'timeSlots', 'blockedDates'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'session_type' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'message' => 'nullable|string',
            'admin_response' => 'nullable|string',
            'status' => 'required|string|in:verification_pending,pending,approved,declined,canceled',
        ]);

        $newStatus = $request->status;

        // Check if date is blocked (if changed)
        if ($booking->booking_date->format('Y-m-d') !== Carbon::parse($request->booking_date)->format('Y-m-d')) {
            $isBlocked = BlockedDate::whereDate('blocked_date', $request->booking_date)->exists();
            if ($isBlocked) {
                return back()->withErrors(['booking_date' => 'Warning: The selected date is blocked on the calendar. Please select another date.'])->withInput();
            }
        }

        // Check for capacity conflict if approving
        if ($newStatus === 'approved') {
            $tempBooking = new Booking([
                'id' => $booking->id,
                'booking_date' => $request->booking_date,
                'time_slot_id' => $request->time_slot_id,
            ]);
            if ($tempBooking->hasConflict()) {
                return back()->withErrors(['time_slot_id' => 'Capacity Conflict: The selected slot has already reached its capacity. Choose another slot or set status to Pending.'])->withInput();
            }
        }

        $oldStatus = $booking->status;

        // Handle status change and soft deletes
        if ($newStatus === 'canceled') {
            $booking->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'session_type' => $request->session_type,
                'booking_date' => $request->booking_date,
                'time_slot_id' => $request->time_slot_id,
                'message' => $request->message,
                'admin_response' => $request->admin_response,
                'status' => 'canceled',
            ]);
            if (!$booking->trashed()) {
                $booking->delete();
            }
        } else {
            if ($booking->trashed()) {
                $booking->restore();
            }
            $booking->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'session_type' => $request->session_type,
                'booking_date' => $request->booking_date,
                'time_slot_id' => $request->time_slot_id,
                'message' => $request->message,
                'admin_response' => $request->admin_response,
                'status' => $newStatus,
            ]);
        }

        // Send status change notification if enabled and status actually changed
        if ($oldStatus !== $newStatus && Setting::get('booking_notifications_enabled', '1') === '1') {
            $booking->notify(new BookingStatusChangedNotification($booking));
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }
}
