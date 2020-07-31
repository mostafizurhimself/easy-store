<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplierNotification extends Notification
{
    use Queueable;

    private $supplier;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return \Mirovit\NovaNotifications\Notification::make()
            ->info('A new supplier was created.')
            ->subtitle('There is a new supplier in the system - ' . $this->supplier->name . '!')
            ->routeDetail('suppliers', $this->supplier->id)
            ->toArray();

    }
}
