<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingVerificationNotification extends Notification implements ShouldQueue
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
        $url = route('bookings.verify', ['token' => $this->booking->verification_token]);

        return (new MailMessage)
            ->subject('Verify Your Booking - Abady Photography')
            ->greeting('Hello ' . $this->booking->name . ',')
            ->line('Thank you for requesting a photo session with Abady Photography.')
            ->line('Your unique booking reference is: **' . $this->booking->reference_number . '**')
            ->line('Please click the button below to verify your booking request. Bookings must be verified before the photographer reviews them.')
            ->action('Verify Booking Request', $url)
            ->line('If you did not make this request, please ignore this email.');
    }
}
