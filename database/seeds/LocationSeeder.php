<?php

use App\Models\Location;
use App\Enums\LocationType;
use App\Enums\LocationStatus;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::unsetEventDispatcher();
        Location::updateOrCreate(
            ['name'          => 'Hazipara'],
            [
            'name'          => 'Hazipara',
            'readable_id'   => 'LO001',
            'type'          => LocationType::FACTORY()->getValue(),
            'status'        => LocationStatus::ACTIVE()->getValue()
        ]);

        Location::updateOrCreate(
            ['name'          => 'Chowdhurypara'],
            [
            'name'          => 'Chowdhurypara',
            'readable_id'   => 'LO002',
            'type'          => LocationType::FACTORY()->getValue(),
            'status'        => LocationStatus::ACTIVE()->getValue()
        ]);
    }
}
