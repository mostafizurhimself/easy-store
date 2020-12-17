<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveTrashed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:trashed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will remove the trasded items from the database';

    /**
     * List of the models
     *
     * @var array
     */
    protected $models = [
        // For Fabrics
        \App\Models\FabricPurchaseItem::class,
        \App\Models\FabricReceiveItem::class,
        \App\Models\FabricReturnItem::class,
        \App\Models\FabricTransferItem::class,
        \App\Models\FabricTransferReceiveItem::class,

        // For Materials
        \App\Models\MaterialPurchaseItem::class,
        \App\Models\MaterialReceiveItem::class,
        \App\Models\MaterialReturnItem::class,
        \App\Models\MaterialTransferItem::class,
        \App\Models\MaterialTransferReceiveItem::class,

        // For Asset
        \App\Models\AssetPurchaseItem::class,
        \App\Models\AssetReceiveItem::class,
        \App\Models\AssetRequisitionItem::class,
        \App\Models\AssetReturnItem::class,
        \App\Models\AssetDistributionItem::class,
        \App\Models\AssetDistributionReceiveItem::class,

        // For Service
        \App\Models\ServiceDispatch::class,
        \App\Models\ServiceReceive::class,
        \App\Models\ServiceTransferItem::class,
        \App\Models\ServiceTransferReceiveItem::class,

        // For Products
        \App\Models\Finishing::class
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach($this->models as $model){
            $items = $model::onlyTrashed()->get();

            foreach($items as $item){
                $itemId = $item->id;
                $itemType = get_class($item);

                // Delete the item
                $item->forceDelete();

                $this->info("Item Type: $itemType, Item Id: $itemId - removed successfully.");
            }

        }
    }
}
