<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\BlockedDate;
use App\Models\Setting;
use App\Notifications\BookingStatusChangedNotification;
use Illuminate\Http\Request;

class ClientBookingController extends Controller
{
    /**
     * Show the lookup form.
     */
    public function showLookupForm()
    {
        return view('bookings.lookup');
    }

    /**
     * Handle the lookup request and authenticate the session.
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string',
            'email' => 'required|email',
        ]);

        $booking = Booking::withTrashed()
            ->where('reference_number', $request->reference_number)
            ->where('email', $request->email)
            ->first();

        if (!$booking) {
            return back()->withErrors(['reference_number' => 'No booking found matching the provided reference number and email address.'])->withInput();
        }

        // Authenticate the client session for this booking
        session(['booking_reference' => $booking->reference_number]);

        return redirect()->route('bookings.portal', ['reference' => $booking->reference_number]);
    }

    /**
     * Show the client booking management page.
     */
    public function show($reference)
    {
        // Check if authorized
        if (session('booking_reference') !== $reference) {
            // Check if user has direct verification/access (we can allow auto-auth from verified session)
            return redirect()->route('bookings.lookup.form')
                ->with('error', 'Please enter your email and reference number to access this booking.')
                ->with('prefilled_reference', $reference);
        }

        $booking = Booking::withTrashed()
            ->where('reference_number', $reference)
            ->firstOrFail();

        $timeSlots = TimeSlot::where('is_active', true)->orderBy('start_time')->get();
        $blockedDates = BlockedDate::pluck('blocked_date')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();

        return view('bookings.show', compact('booking', 'timeSlots', 'blockedDates'));
    }

    /**
     * Update the booking details (only allowed for pending bookings).
     */
    public function update(Request $request, $reference)
    {
        if (session('booking_reference') !== $reference) {
            abort(403, 'Unauthorized action.');
        }

        $booking = Booking::withTrashed()
            ->where('reference_number', $reference)
            ->firstOrFail();

        if (!in_array($booking->status, ['pending', 'verification_pending'])) {
            return back()->with('error', 'You can only edit pending bookings.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'session_type' => 'required|string|max:255',
            'booking_date' => 'required|date|after_or_equal:today',
            'time_slot_id' => 'required|exists:time_slots,id',
            'message' => 'nullable|string',
        ]);

        // Check if date is blocked
        $isBlocked = BlockedDate::whereDate('blocked_date', $request->booking_date)->exists();
        if ($isBlocked) {
            return back()->withErrors(['booking_date' => 'The photographer is unavailable on this date. Please select another date.'])->withInput();
        }

        // Check if slot is active
        $slot = TimeSlot::find($request->time_slot_id);
        if (!$slot || !$slot->is_active) {
            return back()->withErrors(['time_slot_id' => 'The selected time slot is not active. Please select another.'])->withInput();
        }

        $booking->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'session_type' => $request->session_type,
            'booking_date' => $request->booking_date,
            'time_slot_id' => $request->time_slot_id,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Booking details updated successfully.');
    }

    /**
     * Cancel the booking (only allowed for pending bookings).
     */
    public function cancel($reference)
    {
        if (session('booking_reference') !== $reference) {
            abort(403, 'Unauthorized action.');
        }

        $booking = Booking::withTrashed()
            ->where('reference_number', $reference)
            ->firstOrFail();

        if (!in_array($booking->status, ['pending', 'verification_pending'])) {
            return back()->with('error', 'You can only cancel pending bookings.');
        }

        // Update status to canceled
        $booking->update([
            'status' => 'canceled',
        ]);

        // Send cancellation email if enabled
        if (Setting::get('booking_notifications_enabled', '1') === '1') {
            $booking->notify(new BookingStatusChangedNotification($booking));
        }

        // Soft delete the booking
        $booking->delete();

        return back()->with('success', 'Your booking request has been canceled successfully.');
    }
}
