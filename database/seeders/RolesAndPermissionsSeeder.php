<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
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

        $resources = [
            \App\Nova\Location::class,
            \App\Nova\Department::class,
            \App\Nova\Section::class,
            \App\Nova\SubSection::class,
            \App\Nova\Designation::class,
            \App\Nova\Employee::class,

            // Fabric Section
            \App\Nova\FabricCategory::class,
            \App\Nova\Fabric::class,
            \App\Nova\FabricPurchaseOrder::class,
            \App\Nova\FabricPurchaseItem::class,
            \App\Nova\FabricReceiveItem::class,
            \App\Nova\FabricReturnInvoice::class,
            \App\Nova\FabricReturnItem::class,
            \App\Nova\FabricDistribution::class,

            // Material Section
            \App\Nova\MaterialCategory::class,
            \App\Nova\Material::class,
            \App\Nova\MaterialPurchaseOrder::class,
            \App\Nova\MaterialPurchaseItem::class,
            \App\Nova\MaterialReceiveItem::class,
            \App\Nova\MaterialReturnInvoice::class,
            \App\Nova\MaterialReturnItem::class,
            \App\Nova\MaterialDistribution::class,

            // Asset Section
            \App\Nova\AssetCategory::class,
            \App\Nova\Asset::class,
            \App\Nova\AssetPurchaseOrder::class,
            \App\Nova\AssetPurchaseItem::class,
            \App\Nova\AssetReceiveItem::class,
            \App\Nova\AssetReturnInvoice::class,
            \App\Nova\AssetReturnItem::class,
            \App\Nova\AssetRequisition::class,
            \App\Nova\AssetRequisitionItem::class,
            \App\Nova\AssetDistributionInvoice::class,
            \App\Nova\AssetDistributionItem::class,
            \App\Nova\AssetDistributionReceiveItem::class,

            // Service Section
            \App\Nova\ServiceCategory::class,
            \App\Nova\Service::class,
            \App\Nova\ServiceInvoice::class,
            \App\Nova\ServiceDispatch::class,
            \App\Nova\ServiceReceive::class,
            \App\Nova\ServiceTransferInvoice::class,
            \App\Nova\ServiceTransferItem::class,
            \App\Nova\ServiceTransferReceiveItem::class,

            // Product Section
            \App\Nova\ProductCategory::class,
            \App\Nova\Product::class,
            \App\Nova\ProductOutput::class,
            \App\Nova\FinishingInvoice::class,
            \App\Nova\Finishing::class,
            \App\Nova\ProductRequisition::class,
            \App\Nova\ProductRequisitionItem::class,

            // Expense Section
            \App\Nova\Expenser::class,
            \App\Nova\ExpenseCategory::class,
            \App\Nova\Expense::class,
            \App\Nova\Balance::class,

            // Vendor Section
            \App\Nova\Supplier::class,
            \App\Nova\Provider::class,

            // ACL Section
            \App\Nova\User::class,
            \App\Nova\Role::class,

            // Others Section
            \App\Nova\Floor::class,
            \App\Nova\Style::class,
            \App\Nova\Unit::class,
        ];

        foreach($resources as $key=>$resource)
        {
            $name  = $resource::readableName();
            $order = $key*100;
            Permission::updateOrCreate(['name' => 'view ' .$name ],['group' => $name, 'name' => 'view ' . $name, 'group_order' => ($order + 1)]);
            Permission::updateOrCreate(['name' => 'view any ' .$name ],['group' => $name, 'name' => 'view any ' . $name, 'group_order' => ($order + 2)]);
            Permission::updateOrCreate(['name' => 'create ' .$name ],['group' => $name, 'name' => 'create ' . $name, 'group_order' => ($order + 3)]);
            Permission::updateOrCreate(['name' => 'update ' .$name ],['group' => $name, 'name' => 'update ' . $name, 'group_order' => ($order + 4)]);
            Permission::updateOrCreate(['name' => 'delete ' .$name ],['group' => $name, 'name' => 'delete ' . $name, 'group_order' => ($order + 5)]);
            Permission::updateOrCreate(['name' => 'restore ' .$name ],['group' => $name, 'name' => 'restore ' . $name, 'group_order' => ($order + 6)]);
            Permission::updateOrCreate(['name' => 'force delete ' .$name ],['group' => $name, 'name' => 'force delete ' . $name, 'group_order' => ($order + 7)]);

            foreach($resource::$permissions as $permission)
            {
                $name = $permission." ".$resource::readableName();
                $newOrder = $order + 20;
                Permission::updateOrCreate(['name' => $name ],['group' => $resource::readableName(), 'name' => $name, 'group_order' => ($newOrder + 1)]);

                $order++;
            }
        }

        //Exceptional Permissions
        Permission::updateOrCreate(['name' => 'view permissions'],['group' => 'permissions', 'name' => 'view permissions', 'group_order' => 4651]);
        Permission::updateOrCreate(['name' => 'view any permissions'],['group' => 'permissions', 'name' => 'view any permissions', 'group_order' => 4652]);
        Permission::updateOrCreate(['name' => 'assign permissions'],['group' => 'permissions', 'name' => 'assign permissions', 'group_order' => 4653]);

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
        $role = Role::updateOrCreate(['name' => Role::SUPER_ADMIN], ['name' => Role::SUPER_ADMIN, 'display_name' => 'Super Admin']);
        $role->givePermissionTo(Permission::all());
    }
}
