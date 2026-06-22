<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Booking extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'reference_number',
        'name',
        'email',
        'phone',
        'session_type',
        'booking_date',
        'time_slot_id',
        'message',
        'admin_response',
        'status',
        'verification_token',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function timeSlot()
    {
        return $this->belongsTo(TimeSlot::class);
    }

    /**
     * Check if approving this booking would conflict with slot capacity.
     */
    public function hasConflict()
    {
        // Calculate approved bookings for the same date and slot (excluding this booking itself)
        $approvedCount = self::where('booking_date', $this->booking_date)
            ->where('time_slot_id', $this->time_slot_id)
            ->where('status', 'approved')
            ->where('id', '!=', $this->id)
            ->count();

        $capacity = $this->timeSlot ? $this->timeSlot->capacity : 1;

        return $approvedCount >= $capacity;
    }
}
