<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertQuantityNotification extends Notification
{
    use Queueable;

     /**
     * Resource name of the item
     */
    private $resourceUri;

    /**
     * @var mixed
     */
    private $resource;

    /**
     * Resource id of the item
     */
    private $resourceType;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($resourceUri, $resource, $resourceType)
    {
        $this->resourceUri = $resourceUri;
        $this->resource = $resource;
        $this->resourceType = $resourceType;
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
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
                ->error('Low stock quantity alert!')
                ->subtitle("{$this->resourceType} Name: {$this->resource->name}, Quantity: {$this->resource->quantity}")
                ->routeDetail($this->resourceUri, $this->resource->id)
                ->toArray();
    }
}
