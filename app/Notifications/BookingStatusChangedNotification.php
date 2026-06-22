<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusChangedNotification extends Notification implements ShouldQueue
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
        $status = strtolower($this->booking->status);
        $subject = 'Booking Request Updated - ' . $this->booking->reference_number;
        $url = route('bookings.portal', ['reference' => $this->booking->reference_number]);

        $mailMessage = (new MailMessage)
            ->greeting('Hello ' . $this->booking->name . ',');

        if ($status === 'approved') {
            $subject = 'Booking Approved! - ' . $this->booking->reference_number;
            $mailMessage->subject($subject)
                ->line('We are excited to inform you that your photo session booking has been **approved**!')
                ->line('**Booking Reference:** ' . $this->booking->reference_number)
                ->line('**Session Type:** ' . $this->booking->session_type)
                ->line('**Date:** ' . $this->booking->booking_date->format('F d, Y'))
                ->line('**Time Slot:** ' . ($this->booking->timeSlot ? $this->booking->timeSlot->name . ' (' . substr($this->booking->timeSlot->start_time, 0, 5) . ' - ' . substr($this->booking->timeSlot->end_time, 0, 5) . ')' : 'N/A'))
                ->line('Please save this email for your records. If you need to make changes or have any questions, you can access your booking details below.')
                ->action('View Booking Details', $url)
                ->line('Thank you for choosing Abady Photography. We look forward to working with you!');
        } elseif ($status === 'declined') {
            $subject = 'Booking Request Update - ' . $this->booking->reference_number;
            $mailMessage->subject($subject)
                ->line('Thank you for requesting a photo session with Abady Photography.')
                ->line('Unfortunately, we are unable to accept your booking request at this time.')
                ->line('**Booking Reference:** ' . $this->booking->reference_number)
                ->line('**Session Type:** ' . $this->booking->session_type)
                ->line('**Date:** ' . $this->booking->booking_date->format('F d, Y'))
                ->line('**Time Slot:** ' . ($this->booking->timeSlot ? $this->booking->timeSlot->name : 'N/A'))
                ->line('You can look for other available slots and submit a new request if you wish.')
                ->action('Book Another Session', route('bookings.index'));
        } elseif ($status === 'canceled') {
            $subject = 'Booking Canceled - ' . $this->booking->reference_number;
            $mailMessage->subject($subject)
                ->line('As requested, your booking request has been **canceled**.')
                ->line('**Booking Reference:** ' . $this->booking->reference_number)
                ->line('**Session Type:** ' . $this->booking->session_type)
                ->line('**Date:** ' . $this->booking->booking_date->format('F d, Y'))
                ->line('If you did not request this cancellation or would like to book a new session, please visit our booking page.')
                ->action('Book New Session', route('bookings.index'));
        } else {
            $mailMessage->subject($subject)
                ->line('Your booking status has been updated to: **' . ucfirst($status) . '**.')
                ->line('**Booking Reference:** ' . $this->booking->reference_number)
                ->action('View Booking Details', $url);
        }

        return $mailMessage;
    }
}
