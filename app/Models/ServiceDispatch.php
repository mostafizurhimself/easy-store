<?php

namespace App\Models;

use App\Traits\CamelCasing;
use App\Enums\DispatchStatus;
use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceDispatch extends Model
{
    use LogsActivity, SoftDeletes, CamelCasing, HasReadableIdWithDate;

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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['date', 'location'];
    /**
     * Set the model readable id prefix
     *
     * @var string
     */
    public static function readableIdPrefix()
    {
        return "SD";
    }

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
    public function service()
    {
        return $this->belongsTo(Service::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(ServiceInvoice::class, 'invoice_id')->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receives()
    {
        return $this->hasMany(ServiceReceive::class, 'dispatch_id');
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
     * Get the date of the model
     *
     * @return string
     */
    public function getDateAttribute()
    {
        return $this->invoice->date->format('Y-m-d');
    }

    /**
     * Get the location of the model
     *
     * @return string
     */
    public function getLocationAttribute()
    {
        return $this->invoice->location;
    }

    /**
     * Scope a query to only include draft distributions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', DisPatchStatus::DRAFT());
    }

    /**
     * Update total receive amount
     *
     * @return double
     */
    public function updateReceiveAmount()
    {
        $this->receiveAmount = $this->receives->sum('amount');
        $this->save();
    }

    /**
     * Update total receive quantity
     *
     * @return double
     */
    public function updateReceiveQuantity()
    {
        $this->receiveQuantity = $this->receives->sum('quantity');
        $this->save();
    }

    /**
     * Get the remaining quantity attribute
     *
     * @return double
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->dispatchQuantity - $this->receiveQuantity;
    }

    /**
     * Update purchase item status
     *
     * @return void
     */
    public function updateStatus()
    {
        if ($this->receives()->exists() && ($this->dispatchQuantity == $this->receiveQuantity)) {
            $this->status = Dispatchstatus::RECEIVED();
        }

        if ($this->receives()->exists() && ($this->dispatchQuantity != $this->receiveQuantity)) {
            $this->status = Dispatchstatus::PARTIAL();
        }

        if (!$this->receives()->exists()) {
            $this->status = Dispatchstatus::CONFIRMED();
        }

        $this->save();
    }
}
