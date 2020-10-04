<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::updateOrCreate(
            [
                'name' => Setting::APPLICATION_SETTINGS,
            ],
            [
                'name' => Setting::APPLICATION_SETTINGS,
                'settings' => [
                    'name'   => null,
                    'email'  => null,
                    'mobile' => null
                ]
            ]
        );

        Setting::updateOrCreate(
            [
                'name' => Setting::COMPANY_SETTINGS,
            ],
            [
                'name' => Setting::COMPANY_SETTINGS,
                'settings' => [
                    'name'   => null,
                    'email'  => null,
                    'mobile' => null
                ]
            ]
        );


        Setting::updateOrCreate(
            [
                'name' => Setting::PREFIX_SETTINGS,
            ],
            [
                'name' => Setting::PREFIX_SETTINGS,
                'settings' => [
                    'location' => null,
                    'provider' => null,
                    'supplier' => null
                ]
            ]
        );
    }
}
