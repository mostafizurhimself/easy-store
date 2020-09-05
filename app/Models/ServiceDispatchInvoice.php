<?php

namespace App\Models;

use App\Traits\HasReadableIdWithDate;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceDispatchInvoice extends Model
{
    use LogsActivity, HasReadableIdWithDate, SoftDeletes;

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

    public static function readableIdPrefix()
    {
        return "SDI";
    }

    /**
     * Set the model readable id length
     *
     * @var int
     */
    protected static $readableIdLength = 4;

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dispatches()
    {
       return $this->hasMany(ServiceDispatch::class, 'invoice_id');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function provider()
    {
       return $this->belongsTo(Provider::class)->withTrashed();
    }

    /**
     * Get the purchase service ids as an array
     *
     * @return array
     */
    public function serviceIds($id = null)
    {
        return $this->dispatches->whereNotIn('service_id', [$id])->pluck('service_id')->toArray();
    }

    /**
     * Update the total amount
     *
     * @return void
     */
    public function updateTotalAmount()
    {
        $this->totalAmount = $this->dispatches->sum('amount');
        $this->save();
    }


}
