<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;

class Setting extends Model implements HasMedia
{
    use LogsActivity, InteractsWithMedia;

    /**
     * The setting name of application settings
     *
     * @var string
     */
    const APPLICATION_SETTINGS = 'Application Settings';

    /**
     * The setting name of owner company settings
     *
     * @var string
     */
    const COMPANY_SETTINGS = 'Company Settings';

    /**
     * The setting name of readable id prefix setting
     *
     * @var string
     */
    const PREFIX_SETTINGS = 'Prefix Settings';

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
     * Register the media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
       $this->addMediaCollection('settings')->singleFile();
    }

}
