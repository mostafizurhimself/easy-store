<?php

namespace Database\Seeders;

use App\Models\Floor;
use Illuminate\Database\Seeder;

class FloorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Floor::updateOrCreate(
            [
                'location_id' => 1,
                'name' => '3rd Floor',
            ],
            [
                'location_id' => 1,
                'name' => '3rd Floor',
                'number' => '3rd'
            ]
        );

        Floor::updateOrCreate(
            [
                'location_id' => 1,
                'name' => '4th Floor',
            ],
            [
                'location_id' => 1,
                'name' => '4th Floor',
                'number' => '4th'
            ]
        );

        Floor::updateOrCreate(
            [
                'location_id' => 1,
                'name' => '5th Floor',
            ],
            [
                'location_id' => 1,
                'name' => '5th Floor',
                'number' => '5th'
            ]
        );

        Floor::updateOrCreate(
            [
                'location_id' => 1,
                'name' => '6th Floor',
            ],
            [
                'location_id' => 1,
                'name' => '6th Floor',
                'number' => '6th'
            ]
        );

        //Chowdhurypara

        Floor::updateOrCreate(
            [
                'location_id' => 2,
                'name' => '3rd Floor',
            ],
            [
                'location_id' => 2,
                'name' => '3rd Floor',
                'number' => '3rd'
            ]
        );

        Floor::updateOrCreate(
            [
                'location_id' => 2,
                'name' => '4th Floor',
            ],
            [
                'location_id' => 2,
                'name' => '4th Floor',
                'number' => '4th'
            ]
        );

        Floor::updateOrCreate(
            [
                'location_id' => 2,
                'name' => '5th Floor',
            ],
            [
                'location_id' => 2,
                'name' => '5th Floor',
                'number' => '5th'
            ]
        );

        Floor::updateOrCreate(
            [
                'location_id' => 2,
                'name' => '6th Floor',
            ],
            [
                'location_id' => 2,
                'name' => '6th Floor',
                'number' => '6th'
            ]
        );

    }
}
