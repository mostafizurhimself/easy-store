<?php

namespace App\Events;

use App\Models\FabricPurchaseOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FabricPurchaseOrderConfirmed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $purchaseOrder;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FabricPurchaseOrder $purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastAs()
    {
        return 'FabricPurchaseOrderConfirmed';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $users = \App\Models\User::permission('view fabric purchase orders')->get();

        $channelsArray = [];
        foreach($users as $user){

            $channelsArray[] = new PrivateChannel('App.Models.User.'.$user->id);
        }

        return $channelsArray;
        // return new PrivateChannel('channel-name');
    }
}
