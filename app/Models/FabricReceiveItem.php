<?php

namespace App\Models;

use App\Traits\CamelCasing;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class FabricReceiveItem extends Model implements HasMedia
{
    use LogsActivity, CamelCasing, SoftDeletes, HasReadableIdWithDate, InteractsWithMedia;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "RIF";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 6;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date'];

    /**
     * Add all attributes that are not listed in $guarded for log
     *
     * @var boolean
     */
    protected static $logUnguarded = true;

    /**
     * Register the media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
       $this->addMediaCollection('receive-item-attachments');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseOrder()
    {
       return $this->belongsTo(FabricPurchaseOrder::class, 'purchase_order_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaseItem()
    {
       return $this->belongsTo(FabricPurchaseItem::class, 'purchase_item_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fabric()
    {
       return $this->belongsTo(Fabric::class, 'fabric_id');
    }

    /**
     * Get the unit for the fabrics
     *
     * @return string
     */
    public function getUnitAttribute()
    {
        return $this->fabric->unit->name;
    }
}
