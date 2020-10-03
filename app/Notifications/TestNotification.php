<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Coreproc\NovaNotificationFeed\Notifications\NovaBroadcastMessage;

class TestNotification extends Notification
{
    use Queueable;

    protected $level = 'info';
    protected $message = '';

    /**
     * Create a new notification instance.
     *
     * @param $level
     * @param $message
     */
    public function __construct($level, $message = 'Test message')
    {
        $this->level = $level;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            'database',
            'broadcast',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'level' => $this->level,
            'message' => $this->message,
            'url' => 'http://ahmedshakil.com',
            'target' => '_blank'
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new NovaBroadcastMessage($this->toArray($notifiable));
    }
}
