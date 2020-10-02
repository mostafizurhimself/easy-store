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

            // Organizations
            ['name' => 'locations', 'order' => 100, 'type' => 'resource'],
            ['name' => 'departments', 'order' => 200, 'type' => 'resource'],
            ['name' => 'sections', 'order' => 300, 'type' => 'resource'],
            ['name' => 'sub sections', 'order' => 400, 'type' => 'resource'],
            ['name' => 'designations', 'order' => 500, 'type' => 'resource'],
            ['name' => 'employees', 'order' => 600, 'type' => 'resource'],

            // Fabric Section
            ['name' => 'fabric categories', 'order' => 700, 'type' => 'resource'],
            ['name' => 'fabrics', 'order' => 800, 'type' => 'resource'],
            ['name' => 'update fabrics opening quantity', 'group' => 'fabrics', 'order' => 850, 'type' => 'action'],
            ['name' => 'fabric purchase orders', 'order' => 900, 'type' => 'resource'],
            ['name' => 'confirm fabric purchase orders', 'group' => 'fabric purchase orders', 'order' => 950, 'type' => 'action'],
            ['name' => 'generate fabric purchase orders', 'group' => 'fabric purchase orders', 'order' => 955, 'type' => 'action'],
            ['name' => 'fabric purchase items', 'order' => 1000, 'type' => 'resource'],
            ['name' => 'fabric receive items', 'order' => 1120, 'type' => 'resource'],
            ['name' => 'confirm fabric receive items', 'group' => 'fabric receive items', 'order' => 1110, 'type' => 'action'],
            ['name' => 'fabric return invoices', 'order' => 1120, 'type' => 'resource'],
            ['name' => 'confirm fabric return invoices', 'group' => 'fabric return invoices', 'order' => 1130, 'type' => 'action'],
            ['name' => 'generate fabric return invoices', 'group' => 'fabric return invoices', 'order' => 1135, 'type' => 'action'],
            ['name' => 'fabric return items', 'order' => 1140, 'type' => 'resource'],
            ['name' => 'fabric distributions', 'order' => 1200, 'type' => 'resource'],
            ['name' => 'confirm fabric distributions', 'group' => 'fabric distributions', 'order' => 1210, 'type' => 'action'],

            // Material Section
            ['name' => 'material categories', 'order' => 1300, 'type' => 'resource'],
            ['name' => 'materials', 'order' => 1400, 'type' => 'resource'],
            ['name' => 'update materials opening quantity', 'group' => 'materials', 'order' => 1450, 'type' => 'action'],
            ['name' => 'material purchase orders', 'order' => 1500, 'type' => 'resource'],
            ['name' => 'confirm material purchase orders', 'group' => 'material purchase orders', 'order' => 1550, 'type' => 'action'],
            ['name' => 'generate material purchase orders', 'group' => 'material purchase orders', 'order' => 1555, 'type' => 'action'],
            ['name' => 'material purchase items', 'order' => 1600, 'type' => 'resource'],
            ['name' => 'material receive items', 'order' => 1700, 'type' => 'resource'],
            ['name' => 'confirm material receive items', 'group' => 'material receive items', 'order' => 1710, 'type' => 'action'],
            ['name' => 'material return invoices', 'order' => 1720, 'type' => 'resource'],
            ['name' => 'confirm material return invoices', 'group' => 'material return invoices', 'order' => 1730, 'type' => 'action'],
            ['name' => 'generate material return invoices', 'group' => 'material return invoices', 'order' => 1735, 'type' => 'action'],
            ['name' => 'material return items', 'order' => 1740, 'type' => 'resource'],
            ['name' => 'material distributions', 'order' => 1800, 'type' => 'resource'],
            ['name' => 'confirm material distributions', 'group' => 'material distributions', 'order' => 1810, 'type' => 'action'],

            // Asset Section
            ['name' => 'asset categories', 'order' => 1900, 'type' => 'resource'],
            ['name' => 'assets', 'order' => 2000, 'type' => 'resource'],
            ['name' => 'consume assets', 'group' => 'assets', 'order' => 2050, 'type' => 'action'],
            ['name' => 'update assets opening quantity', 'group' => 'assets', 'order' => 2055, 'type' => 'action'],
            ['name' => 'asset purchase orders', 'order' => 2100, 'type' => 'resource'],
            ['name' => 'confirm asset purchase orders', 'group' => 'asset purchase orders', 'order' => 2150, 'type' => 'action'],
            ['name' => 'generate asset purchase orders', 'group' => 'asset purchase orders', 'order' => 2155, 'type' => 'action'],
            ['name' => 'asset purchase items', 'order' => 2200, 'type' => 'resource'],
            ['name' => 'asset receive items', 'order' => 2300, 'type' => 'resource'],
            ['name' => 'confirm asset receive items', 'group' => 'asset receive items', 'order' => 2310, 'type' => 'action'],
            ['name' => 'asset return invoices', 'order' => 2320, 'type' => 'resource'],
            ['name' => 'confirm asset return invoices', 'group' => 'asset return invoices', 'order' => 2330, 'type' => 'action'],
            ['name' => 'generate asset return invoices', 'group' => 'asset return invoices', 'order' => 2335, 'type' => 'action'],
            ['name' => 'asset return items', 'order' => 2340, 'type' => 'resource'],
            ['name' => 'asset requisitions', 'order' => 2400, 'type' => 'resource'],
            ['name' => 'confirm asset requisitions', 'group' => 'asset requisitions', 'order' => 2450, 'type' => 'action'],
            ['name' => 'generate asset requisitions', 'group' => 'asset requisitions', 'order' => 2455, 'type' => 'action'],
            ['name' => 'asset requisition items', 'order' => 2500, 'type' => 'resource'],
            ['name' => 'asset distribution invoices', 'order' => 2600, 'type' => 'resource'],
            ['name' => 'confirm asset distribution invoices', 'group' => 'asset distribution invoices', 'order' => 2650, 'type' => 'action'],
            ['name' => 'generate asset distribution invoices', 'group' => 'asset distribution invoices', 'order' => 2655, 'type' => 'action'],
            ['name' => 'asset distribution items', 'order' => 2700, 'type' => 'resource'],
            ['name' => 'asset distribution receive items', 'order' => 2800, 'type' => 'resource'],
            ['name' => 'confirm asset distribution receive items', 'group' => 'asset distribution receive items', 'order' => 2810, 'type' => 'action'],

            // Service Section
            ['name' => 'service categories', 'order' => 2900, 'type' => 'resource'],
            ['name' => 'services', 'order' => 3000, 'type' => 'resource'],
            ['name' => 'service invoices', 'order' => 3100, 'type' => 'resource'],
            ['name' => 'confirm service invoices', 'group' => 'service invoices', 'order' => 3150, 'type' => 'action'],
            ['name' => 'generate service invoices', 'group' => 'service invoices', 'order' => 3155, 'type' => 'action'],
            ['name' => 'service dispatches', 'order' => 3200, 'type' => 'resource'],
            ['name' => 'service receives', 'order' => 3300, 'type' => 'resource'],
            ['name' => 'confirm service receives', 'group' => 'service receives', 'order' => 3350, 'type' => 'action'],

            // Product Section
            ['name' => 'product categories', 'order' => 3400, 'type' => 'resource'],
            ['name' => 'products', 'order' => 3500, 'type' => 'resource'],
            ['name' => 'update products opening quantity', 'group' => 'products', 'order' => 3550, 'type' => 'action'],
            ['name' => 'product outputs', 'order' => 3600, 'type' => 'resource'],
            ['name' => 'finishing invoices', 'order' => 3700, 'type' => 'resource'],
            ['name' => 'confirm finishing invoices', 'group' => 'finishing invoices', 'order' => 3750, 'type' => 'action'],
            ['name' => 'generate finishing invoices', 'group' => 'finishing invoices', 'order' => 3755, 'type' => 'action'],
            ['name' => 'finishings', 'order' => 3800, 'type' => 'resource'],

            // Expense Section
            ['name' => 'expensers', 'order' => 3900, 'type' => 'resource'],
            ['name' => 'expense categories', 'order' => 4000, 'type' => 'resource'],
            ['name' => 'expenses', 'order' => 4100, 'type' => 'resource'],
            ['name' => 'confirm expenses', 'group' => 'expenses', 'order' => 4150, 'type' => 'action'],
            ['name' => 'balances', 'order' => 4200, 'type' => 'resource'],
            ['name' => 'confirm balances', 'group' => 'balances', 'order' => 4250, 'type' => 'action'],

            // Vendor Section
            ['name' => 'suppliers', 'order' => 4300, 'type' => 'resource'],
            ['name' => 'update suppliers opening balance', 'group' => 'suppliers', 'order' => 4350, 'type' => 'action'],
            ['name' => 'providers', 'order' => 4400, 'type' => 'resource'],
            ['name' => 'update providers opening balance', 'group' => 'providers', 'order' => 4450, 'type' => 'action'],

            // ACL Section
            ['name' => 'users', 'order' => 4500, 'type' => 'resource'],
            ['name' => 'mark as active', 'group' => 'users', 'order' => 4550, 'type' => 'action'],
            ['name' => 'mark as inactive', 'group' => 'users', 'order' => 4555, 'type' => 'action'],
            ['name' => 'roles', 'order' => 4600, 'type' => 'resource'],

            // Others Section
            ['name' => 'floors', 'order' => 4700, 'type' => 'resource'],
            ['name' => 'styles', 'order' => 4800, 'type' => 'resource'],
            ['name' => 'units', 'order' => 4900, 'type' => 'resource'],
            // ... // List all your Models you want to have Permissions for.
        ]);

        //Resource Permissions
        $collection->where('type', 'resource')->each(function ($item, $key) {
            // create permissions for each collection item
            Permission::updateOrCreate(['name' => 'view ' .$item['name'] ],['group' => $item['name'], 'name' => 'view ' . $item['name'], 'group_order' => ($item['order'] + 1)]);
            Permission::updateOrCreate(['name' => 'view any ' .$item['name'] ],['group' => $item['name'], 'name' => 'view any ' . $item['name'], 'group_order' => ($item['order'] + 2)]);
            Permission::updateOrCreate(['name' => 'create ' .$item['name'] ],['group' => $item['name'], 'name' => 'create ' . $item['name'], 'group_order' => ($item['order'] + 3)]);
            Permission::updateOrCreate(['name' => 'update ' .$item['name'] ],['group' => $item['name'], 'name' => 'update ' . $item['name'], 'group_order' => ($item['order'] + 4)]);
            Permission::updateOrCreate(['name' => 'delete ' .$item['name'] ],['group' => $item['name'], 'name' => 'delete ' . $item['name'], 'group_order' => ($item['order'] + 5)]);
            Permission::updateOrCreate(['name' => 'restore ' .$item['name'] ],['group' => $item['name'], 'name' => 'restore ' . $item['name'], 'group_order' => ($item['order'] + 6)]);
            Permission::updateOrCreate(['name' => 'force delete ' .$item['name'] ],['group' => $item['name'], 'name' => 'force delete ' . $item['name'], 'group_order' => ($item['order'] + 7)]);
        });

        //Action Permissions
        $collection->where('type', 'action')->each(function($item, $key){
            Permission::updateOrCreate(['name' => 'can ' .$item['name'] ],['group' => $item['group'], 'name' => 'can ' . $item['name'], 'group_order' => $item['order']]);
        });

            //Exceptional Permissions
            Permission::updateOrCreate(['name' => 'view permissions'],['group' => 'permissions', 'name' => 'view permissions', 'group_order' => 4651]);
            Permission::updateOrCreate(['name' => 'view any permissions'],['group' => 'permissions', 'name' => 'view any permissions', 'group_order' => 4652]);
            Permission::updateOrCreate(['name' => 'assign permissions'],['group' => 'permissions', 'name' => 'assign permissions', 'group_order' => 4653]);

        //Super Admin Permissions
        // $superAdminCollection = collect([
        //     ['name' => 'activity logs', 'order' => 10000],
        //     ['name' => 'settings', 'order' => 10000],
        // ]);

        // $superAdminCollection->each(function ($item, $key) {
        //     // create permissions for each collection item
        //     Permission::updateOrCreate(['name' => 'view ' .$item['name'] ],['group' => 'super admin', 'name' => 'view ' . $item['name'], 'group_order' => $item['order']]);
        //     Permission::updateOrCreate(['name' => 'view any ' .$item['name'] ],['group' => 'super admin', 'name' => 'view any ' . $item['name'], 'group_order' => $item['order']]);
        //     Permission::updateOrCreate(['name' => 'update ' .$item['name'] ],['group' => 'super admin', 'name' => 'update ' . $item['name'], 'group_order' => $item['order']]);
        //     Permission::updateOrCreate(['name' => 'update ' .$item['name'] ],['group' => 'super admin', 'name' => 'update ' . $item['name'], 'group_order' => $item['order']]);
        // });

        //Only For Super Admin Permissions
        Permission::updateOrCreate(['name' => 'view any settings'],['group' => 'super admin', 'name' => 'view any settings', 'group_order' => 10001]);
        Permission::updateOrCreate(['name' => 'view settings'],['group' => 'super admin', 'name' => 'view settings', 'group_order' => 10002]);
        Permission::updateOrCreate(['name' => 'update settings'],['group' => 'super admin', 'name' => 'update settings', 'group_order' => 10003]);
        Permission::updateOrCreate(['name' => 'view any activity logs'],['group' => 'super admin', 'name' => 'view any activity logs', 'group_order' => 10004]);
        Permission::updateOrCreate(['name' => 'view activity logs'],['group' => 'super admin', 'name' => 'view activity logs', 'group_order' => 10005]);
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
