<?php

use App\Models\User;
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
        User::updateOrCreate(
            ['email' => 'admin@easystore.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'admin@easystore.com',
                'password' => bcrypt('mama@1234')
            ]
        );
    }
}
