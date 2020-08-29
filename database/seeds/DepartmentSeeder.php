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
                'active' => true,
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 1,
                'name' => 'Sewing Department',
                'active' => true,
            ],
            [
                'location_id' => 1,
                'name' => 'Sewing Department',
                'active' => true,
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 1,
                'name' => 'Audit Department',
                'active' => true,
            ],
            [
                'location_id' => 1,
                'name' => 'Audit Department',
                'active' => true,
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 1,
                'name' => 'Distribution Department',
                'active' => true,
            ],
            [
                'location_id' => 1,
                'name' => 'Distribution Department',
                'active' => true,
            ]
        );

        //Chowdhurypara

        Department::updateOrCreate(
            [
                'location_id' => 2,
                'name' => 'Finishing Department',
                'active' => true,
            ],
            [
                'location_id' => 2,
                'name' => 'Finishing Department',
                'active' => true,
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 2,
                'name' => 'Sewing Department',
                'active' => true,
            ],
            [
                'location_id' => 2,
                'name' => 'Sewing Department',
                'active' => true,
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 2,
                'name' => 'Audit Department',
                'active' => true,
            ],
            [
                'location_id' => 2,
                'name' => 'Audit Department',
                'active' => true,
            ]
        );

        Department::updateOrCreate(
            [
                'location_id' => 2,
                'name' => 'Distribution Department',
                'active' => true,
            ],
            [
                'location_id' => 2,
                'name' => 'Distribution Department',
                'active' => true,
            ]
        );
    }
}
