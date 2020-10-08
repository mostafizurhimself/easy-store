<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::updateOrCreate(
            [
                'name' => 'pcs'
            ],
            [
                'name'         => 'pcs',
                'display_name' => 'Pices'
            ]
        );

        Unit::updateOrCreate(
            [
                'name' => 'roll'
            ],
            [
                'name'         => 'roll',
                'display_name' => 'Roll'
            ]
        );

        Unit::updateOrCreate(
            [
                'name' => 'kg'
            ],
            [
                'name'         => 'kg',
                'display_name' => 'Kilogram'
            ]
        );
    }
}
