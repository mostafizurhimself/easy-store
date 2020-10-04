<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DistributionConfirmed extends Notification
{
    use Queueable;

    /**
     * Resource name of the purchase order
     */
    private $resourceName;

    /**
     * Resource id or the purchase order
     */
    private $distribution;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($resourceName, $distribution)
    {
        $this->resourceName = $resourceName;
        $this->distribution  = $distribution;
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
            ->info('A new new distribution invoice has been sent.')
            ->subtitle("Invoice No: {$this->distribution->readableId}, Location: {$this->distribution->location->name}")
            ->routeDetail($this->resourceName, $this->distribution->id)
            ->toArray();
    }
}
