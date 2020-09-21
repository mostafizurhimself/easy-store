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
            ['name' => 'locations', 'order' => 100],
            ['name' => 'departments', 'order' => 200],
            ['name' => 'sections', 'order' => 300],
            ['name' => 'sub sections', 'order' => 400],
            ['name' => 'designations', 'order' => 500],
            ['name' => 'employees', 'order' => 600],
            ['name' => 'fabric categories', 'order' => 700],
            ['name' => 'fabrics', 'order' => 800],
            ['name' => 'fabric purchase orders', 'order' => 900],
            ['name' => 'fabric purchase items', 'order' => 1000],
            ['name' => 'fabric receive items', 'order' => 1100],
            ['name' => 'fabric distributions', 'order' => 1200],
            ['name' => 'material categories', 'order' => 1300],
            ['name' => 'materials', 'order' => 1400],
            ['name' => 'material purchase orders', 'order' => 1500],
            ['name' => 'material purchase items', 'order' => 1600],
            ['name' => 'material receive items', 'order' => 1700],
            ['name' => 'material distributions', 'order' => 1800],
            ['name' => 'asset categories', 'order' => 1900],
            ['name' => 'assets', 'order' => 2000],
            ['name' => 'asset purchase orders', 'order' => 2100],
            ['name' => 'asset purchase items', 'order' => 2200],
            ['name' => 'asset receive items', 'order' => 2300],
            ['name' => 'asset requisitions', 'order' => 2400],
            ['name' => 'asset requisition items', 'order' => 2500],
            ['name' => 'asset distribution invoices', 'order' => 2600],
            ['name' => 'asset distribution items', 'order' => 2700],
            ['name' => 'asset distribution receive items', 'order' => 2800],
            ['name' => 'service categories', 'order' => 2900],
            ['name' => 'services', 'order' => 3000],
            ['name' => 'service invoices', 'order' => 3100],
            ['name' => 'service dispatches', 'order' => 3200],
            ['name' => 'service receives', 'order' => 3300],
            ['name' => 'product categories', 'order' => 3400],
            ['name' => 'products', 'order' => 3500],
            ['name' => 'product outputs', 'order' => 3600],
            ['name' => 'finishing invoices', 'order' => 3700],
            ['name' => 'finishings', 'order' => 3800],
            ['name' => 'expensers', 'order' => 3900],
            ['name' => 'expense categories', 'order' => 4000],
            ['name' => 'expenses', 'order' => 4100],
            ['name' => 'balances', 'order' => 4200],
            ['name' => 'suppliers', 'order' => 4300],
            ['name' => 'providers', 'order' => 4400],
            ['name' => 'users', 'order' => 4500],
            ['name' => 'roles', 'order' => 4600],
            ['name' => 'floors', 'order' => 4700],
            ['name' => 'styles', 'order' => 4800],
            ['name' => 'units', 'order' => 4900],
            // ... // List all your Models you want to have Permissions for.
        ]);

        $collection->each(function ($item, $key) {
            // create permissions for each collection item
            Permission::updateOrCreate(['name' => 'view ' .$item['name'] ],['group' => $item['name'], 'name' => 'view ' . $item['name'], 'group_order' => ($item['order'] + 1)]);
            Permission::updateOrCreate(['name' => 'view any ' .$item['name'] ],['group' => $item['name'], 'name' => 'view any ' . $item['name'], 'group_order' => ($item['order'] + 2)]);
            Permission::updateOrCreate(['name' => 'create ' .$item['name'] ],['group' => $item['name'], 'name' => 'create ' . $item['name'], 'group_order' => ($item['order'] + 3)]);
            Permission::updateOrCreate(['name' => 'update ' .$item['name'] ],['group' => $item['name'], 'name' => 'update ' . $item['name'], 'group_order' => ($item['order'] + 4)]);
            Permission::updateOrCreate(['name' => 'delete ' .$item['name'] ],['group' => $item['name'], 'name' => 'delete ' . $item['name'], 'group_order' => ($item['order'] + 5)]);
            Permission::updateOrCreate(['name' => 'restore ' .$item['name'] ],['group' => $item['name'], 'name' => 'restore ' . $item['name'], 'group_order' => ($item['order'] + 6)]);
            Permission::updateOrCreate(['name' => 'force delete ' .$item['name'] ],['group' => $item['name'], 'name' => 'force delete ' . $item['name'], 'group_order' => ($item['order'] + 7)]);
        });

            //Exceptional Permissions
            Permission::updateOrCreate(['name' => 'view permissions'],['group' => 'permissions', 'name' => 'view permissions', 'group_order' => 4651]);
            Permission::updateOrCreate(['name' => 'view any permissions'],['group' => 'permissions', 'name' => 'view any permissions', 'group_order' => 4652]);
            Permission::updateOrCreate(['name' => 'assign permissions'],['group' => 'permissions', 'name' => 'assign permissions', 'group_order' => 4653]);


        //Super Admin Permissions
        $superAdminCollection = collect([
            ['name' => 'activity logs', 'order' => 10000],
            ['name' => 'settings', 'order' => 10000],
        ]);

        $superAdminCollection->each(function ($item, $key) {
            // create permissions for each collection item
            Permission::updateOrCreate(['name' => 'view ' .$item['name'] ],['group' => 'super admin', 'name' => 'view ' . $item['name'], 'group_order' => $item['order']]);
            Permission::updateOrCreate(['name' => 'view any ' .$item['name'] ],['group' => 'super admin', 'name' => 'view any ' . $item['name'], 'group_order' => $item['order']]);
        });

        //Only For Super Admin Permissions
        Permission::updateOrCreate(['name' => 'view all locations data'],['group' => 'super admin', 'name' => 'view all locations data', 'group_order' => 20001]);
        Permission::updateOrCreate(['name' => 'view any locations data'],['group' => 'super admin', 'name' => 'view any locations data', 'group_order' => 20002]);
        Permission::updateOrCreate(['name' => 'create all locations data'],['group' => 'super admin', 'name' => 'create all locations data', 'group_order' => 20003]);
        Permission::updateOrCreate(['name' => 'update all locations data'],['group' => 'super admin', 'name' => 'update all locations data', 'group_order' => 20004]);
        Permission::updateOrCreate(['name' => 'delete all locations data'],['group' => 'super admin', 'name' => 'delete all locations data', 'group_order' => 20005]);
        Permission::updateOrCreate(['name' => 'restore all locations data'],['group' => 'super admin', 'name' => 'restore all locations data', 'group_order' => 20006]);
        Permission::updateOrCreate(['name' => 'force delete all locations data'],['group' => 'super admin', 'name' => 'force delete all locations data', 'group_order' => 20007]);

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
