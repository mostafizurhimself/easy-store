<?php

namespace App\Listeners;

use App\Notifications\FabricPurchaseOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FabricPurchaseOrderConfirmedListner
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->notifiable = \App\Models\User::permission('view fabric purchase orders')->get();

        foreach($this->notifiable as $user){
            $user->notify(new FabricPurchaseOrderNotification($event->purchaseOrder));
        }
    }
}
