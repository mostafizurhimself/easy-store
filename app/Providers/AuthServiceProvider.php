<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\Asset;
use App\Models\Floor;
use App\Models\Style;
use App\Models\Fabric;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Material;
use App\Models\Provider;
use App\Models\Supplier;
use App\Models\Department;
use App\Models\Permission;
use App\Models\Designation;
use App\Policies\RolePolicy;
use App\Policies\UnitPolicy;
use App\Policies\UserPolicy;
use App\Models\AssetCategory;
use App\Policies\AssetPolicy;
use App\Policies\FloorPolicy;
use App\Policies\StylePolicy;
use App\Models\FabricCategory;
use App\Policies\FabricPolicy;
use App\Policies\SectionPolicy;
use App\Policies\SettingPolicy;
use App\Models\MaterialCategory;
use App\Policies\EmployeePolicy;
use App\Policies\LocationPolicy;
use App\Policies\MaterialPolicy;
use App\Policies\ProviderPolicy;
use App\Policies\SupplierPolicy;
use App\Models\FabricReceiveItem;
use App\Models\FabricPurchaseItem;
use App\Policies\DepartmentPolicy;
use App\Policies\PermissionPolicy;
use App\Models\FabricPurchaseOrder;
use App\Models\MaterialReceiveItem;
use App\Policies\ActivityLogPolicy;
use App\Policies\DesignationPolicy;
use App\Models\MaterialPurchaseItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\MaterialPurchaseOrder;
use App\Policies\AssetCategoryPolicy;
use App\Policies\FabricCategoryPolicy;
use Spatie\Activitylog\Models\Activity;
use App\Policies\MaterialCategoryPolicy;
use App\Policies\FabricReceiveItemPolicy;
use App\Policies\FabricPurchaseItemPolicy;
use App\Policies\FabricPurchaseOrderPolicy;
use App\Policies\MaterialReceiveItemPolicy;
use App\Policies\MaterialPurchaseItemPolicy;
use App\Policies\MaterialPurchaseOrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Location::class              => LocationPolicy::class,
        Department::class            => DepartmentPolicy::class,
        Section::class               => SectionPolicy::class,
        Designation::class           => DesignationPolicy::class,
        Employee::class              => EmployeePolicy::class,
        FabricCategory::class        => FabricCategoryPolicy::class,
        Fabric::class                => FabricPolicy::class,
        FabricPurchaseOrder::class   => FabricPurchaseOrderPolicy::class,
        FabricPurchaseItem::class    => FabricPurchaseItemPolicy::class,
        FabricReceiveItem::class     => FabricReceiveItemPolicy::class,
        MaterialCategory::class      => MaterialCategoryPolicy::class,
        Material::class              => MaterialPolicy::class,
        MaterialPurchaseOrder::class => MaterialPurchaseOrderPolicy::class,
        MaterialPurchaseItem::class  => MaterialPurchaseItemPolicy::class,
        MaterialReceiveItem::class   => MaterialReceiveItemPolicy::class,
        AssetCategory::class         => AssetCategoryPolicy::class,
        Asset::class                 => AssetPolicy::class,
        Supplier::class              => SupplierPolicy::class,
        Provider::class              => ProviderPolicy::class,
        Permission::class            => PermissionPolicy::class,
        Role::class                  => RolePolicy::class,
        User::class                  => UserPolicy::class,
        Floor::class                 => FloorPolicy::class,
        Style::class                 => StylePolicy::class,
        Unit::class                  => UnitPolicy::class,
        Activity::class              => ActivityLogPolicy::class,
        Setting::class               => SettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        return Auth::user();
    }
}
