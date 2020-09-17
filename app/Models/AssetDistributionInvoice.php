<?php

namespace App\Models;

use App\Traits\HasDate;
use App\Enums\DistributionStatus;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasReadableIdWithDate;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetDistributionInvoice extends Model implements HasMedia
{
    use LogsActivity, SoftDeletes, InteractsWithMedia, HasReadableIdWithDate, HasDate;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Add all attributes that are not listed in $guarded for log
     *
     * @var boolean
     */
    protected static $logUnguarded = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date'];

    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "AD";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 4;

    /**
     * Register the media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
       $this->addMediaCollection('distribution-attachments');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function distributionItems()
    {
       return $this->hasMany(AssetDistributionItem::class, 'invoice_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
       return $this->belongsTo(Location::class, 'receiver_id')->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requisition()
    {
       return $this->belongsTo(AssetRequisition::class, 'requisition_id')->withTrashed();
    }

    /**
     * Get the purchase assets ids as an array
     *
     * @return array
     */
    public function assetIds($id = null)
    {
        return $this->distributionItems->whereNotIn('asset_id', [$id])->pluck('asset_id')->toArray();
    }

    /**
     * Update the total purchase amount
     *
     * @return void
     */
    public function updateDistributionAmount()
    {
        $this->totalDistributionAmount = $this->distributionItems->sum('distribution_amount');
        $this->save();
    }

    /**
     * Scope a query to only include draft distributions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', DistributionStatus::DRAFT());
    }



}
