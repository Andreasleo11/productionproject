<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FailedMachineJobCreated extends Notification implements ShouldQueue
{
    use Queueable;

    private $details;

    /**
     * Create a new notification instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->greeting('Failed Machine Job Notification')
            ->line('There\'s Failed Machine Job created!')
            ->line('- Machine ID : ' . $this->details['machine_id'])
            ->line('- SPK No : ' . $this->details['spk_no'])
            ->line('- Target : ' . $this->details['target'])
            ->line('- Outstanding : ' . $this->details['outstanding'])
            ->action('See Detail', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
                'message' => 'Failed Machine Job with has just been created!',
            ];
    }
}
