<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(LocationSeeder::class);
        // $this->call(CategorySeeder::class);
        // $this->call(SupplierSeeder::class);
        // $this->call(ProviderSeeder::class);
        // $this->call(DepartmentSeeder::class);
        // $this->call(FloorSeeder::class);
    }
}
