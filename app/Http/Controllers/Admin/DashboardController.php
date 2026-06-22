<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Photo;
use App\Models\Video;
use App\Models\Contact;
use App\Models\Booking;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'albums' => Album::count(),
            'photos' => Photo::count(),
            'videos' => Video::count(),
            'contacts' => Contact::where('is_read', false)->count(),
            'bookings' => Booking::count(),
            'pending_bookings' => Booking::whereIn('status', ['pending', 'verification_pending'])->count(),
        ];

        $latestContacts = Contact::latest()->take(3)->get();
        $latestBookings = Booking::latest()
            ->take(3)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestContacts', 'latestBookings'));
    }
}
