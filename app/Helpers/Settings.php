<?php

namespace App\Helpers;

use App\Models\Setting;

class Settings
{
    /**
     * Get the application name
     *
     * @return object
     */
    public function application()
    {
        return json_decode(Setting::where('name', Setting::APPLICATION_SETTINGS)->first()->settings);
    }

    /**
     * Get the company details
     *
     * @return object
     */
    public function company()
    {
        return json_decode(Setting::where('name', Setting::COMPANY_SETTINGS)->first()->settings);
    }

    /**
     * Get the company logo
     *
     * @return object
     */
    public function companyLogo()
    {
        return Setting::where('name', Setting::COMPANY_SETTINGS)->first()->getMedia('settings')->first() ? Setting::where('name', Setting::COMPANY_SETTINGS)->first()->getMedia('settings')->first() : null;
    }

    /**
     * Get the prefix settings
     *
     * @return object
     */
    public function prefix()
    {
        return json_decode(Setting::where('name', Setting::PREFIX_SETTINGS)->first()->settings);
    }
}
