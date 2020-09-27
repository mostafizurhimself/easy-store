<?php

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::updateOrCreate(
            [
                'location_id' => 1,
                'name' => 'Finishing Department'
            ],
            [
                'location_id' => 1,
                'name' => 'Finishing Department',
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 1,
                'name' => 'Sewing Department',
            ],
            [
                'location_id' => 1,
                'name' => 'Sewing Department',
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 1,
                'name' => 'Audit Department',
            ],
            [
                'location_id' => 1,
                'name' => 'Audit Department',
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 1,
                'name' => 'Distribution Department',
            ],
            [
                'location_id' => 1,
                'name' => 'Distribution Department',
            ]
        );

        //Chowdhurypara

        Department::updateOrCreate(
            [
                'location_id' => 2,
                'name' => 'Finishing Department',
            ],
            [
                'location_id' => 2,
                'name' => 'Finishing Department',
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 2,
                'name' => 'Sewing Department',
            ],
            [
                'location_id' => 2,
                'name' => 'Sewing Department',
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 2,
                'name' => 'Audit Department',
            ],
            [
                'location_id' => 2,
                'name' => 'Audit Department',
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 2,
                'name' => 'Distribution Department',
            ],
            [
                'location_id' => 2,
                'name' => 'Distribution Department',
            ]
        );
    }
}
