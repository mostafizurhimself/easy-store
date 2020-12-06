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
     * Get the model goodsGatePass
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function goodsGatePass()
    {
        return $this->morphOne(GoodsGatePass::class, 'invoice');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receiveItems()
    {
        return $this->hasMany(AssetDistributionReceiveItem::class, 'invoice_id');
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
     * Get the distribuion assets ids as an array
     *
     * @return array
     */
    public function assetIds($id = null)
    {
        return $this->distributionItems->whereNotIn('asset_id', [$id])->pluck('asset_id')->toArray();
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

    /**
     * Update the total distribuion amount
     *
     * @return void
     */
    public function updateDistributionAmount()
    {
        $this->totalDistributionAmount = $this->distributionItems->sum('distribution_amount');
        $this->save();
    }

    /**
     * Update the total receive amount
     *
     * @return void
     */
    public function updateReceiveAmount()
    {
        $this->totalReceiveAmount = $this->receiveItems->sum('amount');
        $this->save();
    }

    /**
     * Check all the distribuion items status is confirmed or not
     *
     * @return bool
     */
    public function isConfirmed()
    {
        $status = $this->distributionItems()->pluck('status')->unique();
        if ($status->count() == 1  && $status->first() == DistributionStatus::CONFIRMED()) {
            return true;
        }
        return false;
    }

    /**
     * Check all the distribuion items status is received or not
     *
     * @return bool
     */
    public function isReceived()
    {
        $status = $this->distributionItems()->pluck('status')->unique();
        if ($status->count() == 1  && $status->first() == DistributionStatus::RECEIVED()) {
            return true;
        }
        return false;
    }

    /**
     * Check any of the distribuion items status is partial or not
     *
     * @return bool
     */
    public function isPartial()
    {
        if ($this->receiveItems()->exists()) {
            return true;
        }
        return false;
    }

    /**
     * Update the distribuion invoice status
     *
     * @return void
     */
    public function updateStatus()
    {
        if ($this->distributionItems()->exists()) {

            if ($this->isConfirmed()) {
                $this->status = DistributionStatus::CONFIRMED();
                $this->save();
                return;
            }

            if ($this->isReceived()) {
                $this->status = DistributionStatus::RECEIVED();
                $this->save();
                return;
            }

            if ($this->isPartial()) {
                $this->status = DistributionStatus::PARTIAL();
                $this->save();
                return;
            }
        } else {
            $this->status = DistributionStatus::DRAFT();
            $this->save();
        }
    }

    /**
     * Get the invoice reference no
     *
     * @return string
     */
    public function getReferenceAttribute()
    {
        return $this->requisition ? $this->requisition->readableId : 'N/A';
    }
}
