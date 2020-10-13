<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionConfirmed extends Notification
{
    use Queueable;

    /**
     * Resource name of the purchase order
     */
    private $resourceName;

    /**
     * Resource id or the purchase order
     */
    private $requisition;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($resourceName, $requisition)
    {
        $this->resourceName = $resourceName;
        $this->requisition  = $requisition;
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
            ->info('A new requisition has been sent.')
            ->subtitle("Requisition No: {$this->requisition->readableId}, Location: {$this->requisition->location->name}")
            ->routeDetail($this->resourceName, $this->requisition->id)
            ->toArray();
    }
}
