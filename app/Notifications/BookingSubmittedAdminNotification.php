<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingSubmittedAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = url('/admin/bookings');

        return (new MailMessage)
            ->subject('New Booking Request - ' . $this->booking->reference_number)
            ->greeting('Hello Admin,')
            ->line('A new booking request has been submitted and verified by the client.')
            ->line('**Reference:** ' . $this->booking->reference_number)
            ->line('**Client Name:** ' . $this->booking->name)
            ->line('**Email:** ' . $this->booking->email)
            ->line('**Phone:** ' . ($this->booking->phone ?? 'N/A'))
            ->line('**Session Type:** ' . $this->booking->session_type)
            ->line('**Date:** ' . $this->booking->booking_date->format('F d, Y'))
            ->line('**Time Slot:** ' . ($this->booking->timeSlot ? $this->booking->timeSlot->name . ' (' . substr($this->booking->timeSlot->start_time, 0, 5) . ' - ' . substr($this->booking->timeSlot->end_time, 0, 5) . ')' : 'N/A'))
            ->line('**Message:** ' . ($this->booking->message ?? 'No message provided'))
            ->action('Manage Bookings', $url);
    }
}
