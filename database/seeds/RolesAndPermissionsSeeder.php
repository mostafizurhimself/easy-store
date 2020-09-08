<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $collection = collect([
            ['name' => 'locations', 'order' => 1],
            ['name' => 'departments', 'order' => 2],
            ['name' => 'sections', 'order' => 3],
            ['name' => 'designations', 'order' => 4],
            ['name' => 'employees', 'order' => 5],
            ['name' => 'fabric categories', 'order' => 11],
            ['name' => 'fabrics', 'order' => 12],
            ['name' => 'fabric purchase orders', 'order' => 13],
            ['name' => 'fabric purchase items', 'order' => 14],
            ['name' => 'fabric receive items', 'order' => 15],
            ['name' => 'fabric distributions', 'order' => 16],
            ['name' => 'material categories', 'order' => 21],
            ['name' => 'materials', 'order' => 22],
            ['name' => 'material purchase orders', 'order' => 23],
            ['name' => 'material purchase items', 'order' => 24],
            ['name' => 'material receive items', 'order' => 25],
            ['name' => 'material distributions', 'order' => 26],
            ['name' => 'asset categories', 'order' => 31],
            ['name' => 'assets', 'order' => 32],
            ['name' => 'asset purchase orders', 'order' => 33],
            ['name' => 'asset purchase items', 'order' => 34],
            ['name' => 'asset receive items', 'order' => 35],
            ['name' => 'service categories', 'order' => 41],
            ['name' => 'services', 'order' => 42],
            ['name' => 'service invoices', 'order' => 43],
            ['name' => 'service dispatches', 'order' => 44],
            ['name' => 'service receives', 'order' => 44],
            ['name' => 'product categories', 'order' => 51],
            ['name' => 'products', 'order' => 52],
            ['name' => 'product outputs', 'order' => 53],
            ['name' => 'finishing invoices', 'order' => 54],
            ['name' => 'finishings', 'order' => 55],
            ['name' => 'expensers', 'order' => 60],
            ['name' => 'expense categories', 'order' => 61],
            ['name' => 'expenses', 'order' => 62],
            ['name' => 'balances', 'order' => 63],
            ['name' => 'suppliers', 'order' => 70],
            ['name' => 'providers', 'order' => 80],
            ['name' => 'users', 'order' => 100],
            ['name' => 'roles', 'order' => 200],
            ['name' => 'floors', 'order' => 300],
            ['name' => 'styles', 'order' => 400],
            ['name' => 'units', 'order' => 500],
            // ... // List all your Models you want to have Permissions for.
        ]);

        $collection->each(function ($item, $key) {
            // create permissions for each collection item
            Permission::updateOrCreate(['name' => 'view ' .$item['name'] ],['group' => $item['name'], 'name' => 'view ' . $item['name'], 'group_order' => $item['order']]);
            Permission::updateOrCreate(['name' => 'view any ' .$item['name'] ],['group' => $item['name'], 'name' => 'view any ' . $item['name'], 'group_order' => $item['order']]);
            Permission::updateOrCreate(['name' => 'create ' .$item['name'] ],['group' => $item['name'], 'name' => 'create ' . $item['name'], 'group_order' => $item['order']]);
            Permission::updateOrCreate(['name' => 'update ' .$item['name'] ],['group' => $item['name'], 'name' => 'update ' . $item['name'], 'group_order' => $item['order']]);
            Permission::updateOrCreate(['name' => 'delete ' .$item['name'] ],['group' => $item['name'], 'name' => 'delete ' . $item['name'], 'group_order' => $item['order']]);
            Permission::updateOrCreate(['name' => 'restore ' .$item['name'] ],['group' => $item['name'], 'name' => 'restore ' . $item['name'], 'group_order' => $item['order']]);
            Permission::updateOrCreate(['name' => 'force delete ' .$item['name'] ],['group' => $item['name'], 'name' => 'force delete ' . $item['name'], 'group_order' => $item['order']]);
        });

            //Exceptional Permissions
            Permission::updateOrCreate(['name' => 'view permissions'],['group' => 'permissions', 'name' => 'view permissions', 'group_order' => 250]);
            Permission::updateOrCreate(['name' => 'view any permissions'],['group' => 'permissions', 'name' => 'view any permissions', 'group_order' => 250]);
            Permission::updateOrCreate(['name' => 'assign permissions'],['group' => 'permissions', 'name' => 'assign permissions', 'group_order' => 250]);


        //Super Admin Permissions
        $superAdminCollection = collect([
            ['name' => 'activity logs', 'order' => 600],
            ['name' => 'settings', 'order' => 700],
        ]);

        $superAdminCollection->each(function ($item, $key) {
            // create permissions for each collection item
            Permission::updateOrCreate(['name' => 'view ' .$item['name'] ],['group' => 'super admin', 'name' => 'view ' . $item['name'], 'group_order' => $item['order']]);
            Permission::updateOrCreate(['name' => 'view any ' .$item['name'] ],['group' => 'super admin', 'name' => 'view any ' . $item['name'], 'group_order' => $item['order']]);
        });

        //Only For Super Admin Permissions
        Permission::updateOrCreate(['name' => 'view all locations data'],['group' => 'super admin', 'name' => 'view all locations data', 'group_order' => 1001]);
        Permission::updateOrCreate(['name' => 'view any locations data'],['group' => 'super admin', 'name' => 'view any locations data', 'group_order' => 1002]);
        Permission::updateOrCreate(['name' => 'create all locations data'],['group' => 'super admin', 'name' => 'create all locations data', 'group_order' => 1003]);
        Permission::updateOrCreate(['name' => 'update all locations data'],['group' => 'super admin', 'name' => 'update all locations data', 'group_order' => 1004]);
        Permission::updateOrCreate(['name' => 'delete all locations data'],['group' => 'super admin', 'name' => 'delete all locations data', 'group_order' => 1005]);
        Permission::updateOrCreate(['name' => 'restore all locations data'],['group' => 'super admin', 'name' => 'restore all locations data', 'group_order' => 1006]);
        Permission::updateOrCreate(['name' => 'force delete all locations data'],['group' => 'super admin', 'name' => 'force delete all locations data', 'group_order' => 1007]);

        // Create a Super-Admin Role and assign all Permissions
        $role = Role::updateOrCreate(['name' => 'super-admin'], ['name' => 'super-admin', 'display_name' => 'Super Admin']);
        $role->givePermissionTo(Permission::all());

        // Give User Super-Admin Role
        $user = App\Models\User::whereEmail('admin@easystore.com')->first(); // Change this to your email.
        if(!$user->hasRole('super-admin')){
            $user->assignRole('super-admin');
        }
    }
}
