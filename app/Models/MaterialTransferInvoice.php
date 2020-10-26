<?php

namespace App\Models;

use App\Traits\HasDate;
use App\Enums\TransferStatus;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasReadableIdWithDate;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialTransferInvoice extends Model implements HasMedia
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
        return "MTI";
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
       $this->addMediaCollection('transfer-attachments');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferItems()
    {
       return $this->hasMany(MaterialTransferItem::class, 'invoice_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receiveItems()
    {
       return $this->hasMany(MaterialTransferReceiveItem::class, 'invoice_id');
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
     * Get the transfer materials ids as an array
     *
     * @return array
     */
    public function materialIds($id = null)
    {
        return $this->transferItems->whereNotIn('material_id', [$id])->pluck('material_id')->toArray();
    }

    /**
     * Scope a query to only include draft distributions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', TransferStatus::DRAFT());
    }

    /**
     * Update the total transfer amount
     *
     * @return void
     */
    public function updateTransferAmount()
    {
        $this->totalTransferAmount = $this->transferItems->sum('transfer_amount');
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
     * Check all the transfer items status is confirmed or not
     *
     * @return bool
     */
    public function isConfirmed()
    {
        $status = $this->transferItems()->pluck('status')->unique();
        if($status->count() == 1  && $status->first() == TransferStatus::CONFIRMED()){
            return true;
        }
        return false;
    }

    /**
     * Check all the transfer items status is received or not
     *
     * @return bool
     */
    public function isReceived()
    {
        $status = $this->transferItems()->pluck('status')->unique();
        if($status->count() == 1  && $status->first() == TransferStatus::RECEIVED()){
            return true;
        }
        return false;
    }

    /**
     * Check any of the transfer items status is partial or not
     *
     * @return bool
     */
    public function isPartial()
    {
        if($this->receiveItems()->exists()){
            return true;
        }
        return false;
    }

    /**
     * Update the transfer invoice status
     *
     * @return void
     */
    public function updateStatus()
    {
        if($this->transferItems()->exists()){

            if($this->isConfirmed()){
                $this->status = TransferStatus::CONFIRMED();
                $this->save();
                return;
            }

            if($this->isReceived()){
                $this->status = TransferStatus::RECEIVED();
                $this->save();
                return;
            }

            if($this->isPartial()){
                $this->status = TransferStatus::PARTIAL();
                $this->save();
                return;
            }

        }else{
            $this->status = TransferStatus::DRAFT();
            $this->save();
        }
    }

}
