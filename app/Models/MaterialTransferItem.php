<?php

namespace App\Models;

use App\Traits\CamelCasing;
use App\Enums\TransferStatus;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialTransferItem extends Model
{
    use LogsActivity, CamelCasing, HasReadableIdWithDate;

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
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "MT";
    }

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['unit'];

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 5;

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
       return $this->belongsTo(MaterialTransferInvoice::class, 'invoice_id')->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function material()
    {
       return $this->belongsTo(Material::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receiveItems()
    {
       return $this->hasMany(MaterialTransferReceiveItem::class, 'transfer_item_id');
    }

    /**
     * Get the transfer item remaining receive quantity
     *
     * @return double
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->transferQuantity - $this->receiveQuantity;
    }

    /**
     * Scope a query to only include draft transfers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', TransferStatus::DRAFT());
    }

        /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit()
    {
       return $this->belongsTo(Unit::class)->withTrashed();
    }

    /**
     * Get the unit for the model
     *
     * @return string
     */
    public function getUnitNameAttribute()
    {
        return $this->unit->name;
    }


    /**
     * Update total receive quantity
     *
     * @return void
     */
    public function updateReceiveQuantity()
    {
        $this->receiveQuantity = $this->receiveItems->sum('quantity');
        $this->save();
    }

     /**
     * Update total receive amount
     *
     * @return void
     */
    public function updateReceiveAmount()
    {
        $this->receiveAmount = $this->receiveItems->sum('amount');
        $this->save();
    }

    /**
     * Update distribution item status
     *
     * @return void
     */
    public function updateStatus()
    {
        if($this->receiveItems()->exists() && ($this->transferQuantity == $this->receiveQuantity)){
            $this->status= TransferStatus::RECEIVED();
        }

        if($this->receiveItems()->exists() && ($this->transferQuantity != $this->receiveQuantity)){
            $this->status= TransferStatus::PARTIAL();
        }

        if(!$this->receiveItems()->exists()){
            $this->status= TransferStatus::CONFIRMED();
        }

        $this->save();

    }

}
