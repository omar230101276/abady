<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingSubmittedClientNotification extends Notification implements ShouldQueue
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
        $url = route('bookings.portal', ['reference' => $this->booking->reference_number]);

        return (new MailMessage)
            ->subject('Booking Request Confirmed - Abady Photography')
            ->greeting('Hello ' . $this->booking->name . ',')
            ->line('Your booking request has been successfully verified and is now pending review by the photographer.')
            ->line('**Booking Reference:** ' . $this->booking->reference_number)
            ->line('**Session Type:** ' . $this->booking->session_type)
            ->line('**Date:** ' . $this->booking->booking_date->format('F d, Y'))
            ->line('**Time Slot:** ' . ($this->booking->timeSlot ? $this->booking->timeSlot->name . ' (' . substr($this->booking->timeSlot->start_time, 0, 5) . ' - ' . substr($this->booking->timeSlot->end_time, 0, 5) . ')' : 'N/A'))
            ->line('You can view, edit, or cancel your booking request at any time using our client portal.')
            ->action('Access Client Portal', $url)
            ->line('We will notify you as soon as your booking status is updated.');
    }
}
