<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;


class PurchaseOrderConfirmed extends Notification
{
    use Queueable;

    /**
     * Resource name of the purchase order
     */
    private $resourceName;

    /**
     * Resource id or the purchase order
     */
    private $purchaseOrder;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($resourceName, $purchaseOrder)
    {
        $this->resourceName = $resourceName;
        $this->purchaseOrder = $purchaseOrder;
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
                ->info('A new purchase order has been confirmed')
                ->subtitle("Order No: {$this->purchaseOrder->readableId}, Approved By: {$this->purchaseOrder->approver->name}")
                ->routeDetail($this->resourceName, $this->purchaseOrder->id)
                ->toArray();
    }
}
