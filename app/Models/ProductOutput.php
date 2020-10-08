<?php

namespace App\Models;

use App\Models\Unit;
use App\Facades\Settings;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOutput extends Model
{
    use LogsActivity, SoftDeletes;

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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['category'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $append = ['unit'];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date'];

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
       return $this->belongsTo(ProductCategory::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function style()
    {
       return $this->belongsTo(Style::class)->withTrashed();
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function floor()
    {
       return $this->belongsTo(Floor::class)->withTrashed();
    }

    /**
     * Get the unit of the product
     *
     * @return string
     */
    public function getUnitAttribute()
    {
        if(empty(Settings::application()->output_unit))
        {
            return Unit::first()->name;
        }

        return Unit::find(Settings::application()->output_unit)->name;
    }



}
