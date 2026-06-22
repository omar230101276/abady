<?php

namespace App\Listeners;

use App\Models\NotificationLog;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Events\NotificationFailed;

class LogNotification
{
    /**
     * Handle notification events.
     */
    public function handle($event): void
    {
        if ($event instanceof NotificationSending) {
            $this->sending($event);
        } elseif ($event instanceof NotificationSent) {
            $this->sent($event);
        } elseif ($event instanceof NotificationFailed) {
            $this->failed($event);
        }
    }

    protected function sending(NotificationSending $event): void
    {
        $recipient = null;
        if (method_exists($event->notifiable, 'routeNotificationFor')) {
            $recipient = $event->notifiable->routeNotificationFor('mail');
        }
        if (!$recipient && isset($event->notifiable->email)) {
            $recipient = $event->notifiable->email;
        }
        if (!$recipient) {
            $recipient = is_string($event->notifiable) ? $event->notifiable : 'unknown';
        }

        // Clean recipient string if array or object
        if (is_array($recipient)) {
            $recipient = implode(', ', $recipient);
        }

        $bookingId = null;
        if (property_exists($event->notification, 'booking') && $event->notification->booking) {
            $bookingId = $event->notification->booking->id;
        }

        $log = NotificationLog::create([
            'booking_id' => $bookingId,
            'recipient_email' => $recipient,
            'notification_type' => class_basename($event->notification),
            'channel' => $event->channel,
            'status' => 'pending',
        ]);

        // Attach log ID to the notification so we can update it in Sent/Failed events
        $event->notification->log_id = $log->id;
    }

    protected function sent(NotificationSent $event): void
    {
        if (isset($event->notification->log_id)) {
            $log = NotificationLog::find($event->notification->log_id);
            if ($log) {
                $log->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }
        }
    }

    protected function failed(NotificationFailed $event): void
    {
        if (isset($event->notification->log_id)) {
            $log = NotificationLog::find($event->notification->log_id);
            if ($log) {
                $errorMessage = null;
                if (isset($event->data['exception'])) {
                    $errorMessage = $event->data['exception']->getMessage();
                } elseif (isset($event->data['message'])) {
                    $errorMessage = $event->data['message'];
                }
                $log->update([
                    'status' => 'failed',
                    'error_message' => $errorMessage,
                ]);
            }
        }
    }
}
