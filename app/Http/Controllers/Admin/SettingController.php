<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TimeSlot;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $timeSlots = TimeSlot::orderBy('start_time')->get();
        
        $notificationLogs = NotificationLog::with('booking')
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'logs_page');

        $notificationsEnabled = Setting::get('booking_notifications_enabled', '1') === '1';

        return view('admin.settings.index', compact('timeSlots', 'notificationLogs', 'notificationsEnabled'));
    }

    /**
     * Update global settings.
     */
    public function update(Request $request)
    {
        $enabled = $request->has('booking_notifications_enabled') ? '1' : '0';
        Setting::set('booking_notifications_enabled', $enabled);

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Store a new time slot.
     */
    public function storeTimeSlot(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|string', // e.g. 09:00 or 09:00:00
            'end_time' => 'required|string',
            'capacity' => 'required|integer|min:1',
        ]);

        TimeSlot::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Time slot created successfully.');
    }

    /**
     * Delete a time slot.
     */
    public function destroyTimeSlot($id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        
        // Prevent deletion if it has associated bookings?
        // Actually, cascade delete is configured on database level, but we could soft-block it if desired.
        // Let's just delete it since cascade will handle bookings or we set time_slot_id.
        $timeSlot->delete();

        return back()->with('success', 'Time slot deleted successfully.');
    }
}
