<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\BlockedDate;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\BookingVerificationNotification;
use App\Notifications\BookingSubmittedClientNotification;
use App\Notifications\BookingSubmittedAdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

class BookingController extends Controller
{
    /**
     * Show the booking request page.
     */
    public function index()
    {
        $timeSlots = TimeSlot::where('is_active', true)->orderBy('start_time')->get();
        
        // Get blocked dates as an array of Y-m-d strings to help with frontend disabling (optional but helpful)
        $blockedDates = BlockedDate::pluck('blocked_date')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();

        return view('book', compact('timeSlots', 'blockedDates'));
    }

    /**
     * Store a new booking request.
     */
    public function store(Request $request)
    {
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

        // Generate booking reference
        do {
            $referenceNumber = 'ABD-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Booking::where('reference_number', $referenceNumber)->exists());

        // Generate verification token
        $verificationToken = Str::random(64);

        $booking = Booking::create([
            'reference_number' => $referenceNumber,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'session_type' => $request->session_type,
            'booking_date' => $request->booking_date,
            'time_slot_id' => $request->time_slot_id,
            'message' => $request->message,
            'status' => 'verification_pending',
            'verification_token' => $verificationToken,
        ]);

        // Send email verification link
        if (Setting::get('booking_notifications_enabled', '1') === '1') {
            $booking->notify(new BookingVerificationNotification($booking));
        }

        return redirect()->route('bookings.index')
            ->with('success', 'Your booking request has been successfully received!. We will contact you soon within 24-48 work hours.')
            ->with('booking_reference', $booking->reference_number);
    }

    /**
     * Verify the booking request.
     */
    public function verify($token)
    {
        $booking = Booking::where('verification_token', $token)->first();

        if (!$booking) {
            return redirect()->route('bookings.index')->with('error', 'Invalid or expired verification link.');
        }

        if ($booking->status === 'verification_pending') {
            $booking->update([
                'status' => 'pending',
                'verification_token' => null, // clear token after use
            ]);

            // Dispatch submission notifications
            if (Setting::get('booking_notifications_enabled', '1') === '1') {
                // To Client
                $booking->notify(new BookingSubmittedClientNotification($booking));

                // To Admin
                $adminEmail = User::first()?->email ?? 'admin@abady.com';
                Notification::route('mail', $adminEmail)
                    ->notify(new BookingSubmittedAdminNotification($booking));
            }

            return redirect()->route('bookings.portal', ['reference' => $booking->reference_number])
                ->with('success', 'Thank you! Your email has been verified and your booking request is now pending review.');
        }

        return redirect()->route('bookings.portal', ['reference' => $booking->reference_number])
            ->with('info', 'Your booking has already been verified.');
    }
}
