<?php

namespace Database\Seeders;

use \App\Models\User;
use \App\Models\Location;
use \App\Enums\LocationType;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = Location::where('type', LocationType::BRANCH())->get();

        foreach($locations as $location)
        {
            $user = $location->users()->updateOrCreate(
                [
                    'email' => $location->email
                ],
                [
                    'name'     => "Branch Manager",
                    'email'    => $location->email,
                    'password' => bcrypt(111111)
                ]
            );

            $user->syncRoles('branch-manager');
        }
    }
}
