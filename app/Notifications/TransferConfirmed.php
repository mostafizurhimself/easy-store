<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferConfirmed extends Notification
{
    use Queueable;

    /**
     * Resource name of the purchase order
     */
    private $uriKey;

    /**
     * Resource id or the purchase order
     */
    private $model;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($uriKey, $model)
    {
        $this->uriKey = $uriKey;
        $this->model  = $model;
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
            ->info('A new new transfer invoice has been sent.')
            ->subtitle("Invoice No: {$this->model->readableId}, Location: {$this->model->location->name}")
            ->routeDetail($this->uriKey, $this->model->id)
            ->toArray();
    }
}
