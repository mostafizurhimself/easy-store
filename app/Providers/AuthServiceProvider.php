<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\Asset;
use App\Models\Floor;
use App\Models\Style;
use App\Models\Fabric;
use App\Models\Balance;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Section;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Employee;
use App\Models\Expenser;
use App\Models\Location;
use App\Models\Material;
use App\Models\Provider;
use App\Models\Supplier;
use App\Models\Finishing;
use App\Models\Department;
use App\Models\Permission;
use App\Models\SubSection;
use App\Models\Designation;
use App\Models\AssetConsume;
use App\Policies\RolePolicy;
use App\Policies\UnitPolicy;
use App\Policies\UserPolicy;
use App\Models\AssetCategory;
use App\Models\ProductOutput;
use App\Policies\AssetPolicy;
use App\Policies\FloorPolicy;
use App\Policies\StylePolicy;
use App\Models\FabricCategory;
use App\Models\ServiceInvoice;
use App\Models\ServiceReceive;
use App\Policies\FabricPolicy;
use App\Models\AssetReturnItem;
use App\Models\ExpenseCategory;
use App\Models\ProductCategory;
use App\Models\ServiceCategory;
use App\Models\ServiceDispatch;
use App\Policies\BalancePolicy;
use App\Policies\ExpensePolicy;
use App\Policies\ProductPolicy;
use App\Policies\SectionPolicy;
use App\Policies\ServicePolicy;
use App\Policies\SettingPolicy;
use App\Models\AssetReceiveItem;
use App\Models\AssetRequisition;
use App\Models\FabricReturnItem;
use App\Models\FinishingInvoice;
use App\Models\MaterialCategory;
use App\Policies\EmployeePolicy;
use App\Policies\ExpenserPolicy;
use App\Policies\LocationPolicy;
use App\Policies\MaterialPolicy;
use App\Policies\ProviderPolicy;
use App\Policies\SupplierPolicy;
use App\Models\AssetPurchaseItem;
use App\Models\FabricReceiveItem;
use App\Policies\FinishingPolicy;
use App\Models\AssetPurchaseOrder;
use App\Models\AssetReturnInvoice;
use App\Models\FabricDistribution;
use App\Models\FabricPurchaseItem;
use App\Models\MaterialReturnItem;
use App\Models\ProductRequisition;
use App\Policies\DepartmentPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\SubSectionPolicy;
use App\Models\FabricPurchaseOrder;
use App\Models\FabricReturnInvoice;
use App\Models\MaterialReceiveItem;
use App\Models\ServiceTransferItem;
use App\Policies\ActivityLogPolicy;
use App\Policies\DesignationPolicy;
use App\Models\AssetRequisitionItem;
use App\Models\MaterialDistribution;
use App\Models\MaterialPurchaseItem;
use App\Policies\AssetConsumePolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\AssetDistributionItem;
use App\Models\MaterialPurchaseOrder;
use App\Models\MaterialReturnInvoice;
use App\Policies\AssetCategoryPolicy;
use App\Policies\ProductOutputPolicy;
use App\Models\ProductRequisitionItem;
use App\Models\ServiceTransferInvoice;
use App\Policies\FabricCategoryPolicy;
use App\Policies\ServiceInvoicePolicy;
use App\Policies\ServiceReceivePolicy;
use App\Policies\AssetReturnItemPolicy;
use App\Policies\ExpenseCategoryPolicy;
use App\Policies\ProductCategoryPolicy;
use App\Policies\ServiceCategoryPolicy;
use App\Policies\ServiceDispatchPolicy;
use Spatie\Activitylog\Models\Activity;
use App\Models\AssetDistributionInvoice;
use App\Policies\AssetReceiveItemPolicy;
use App\Policies\AssetRequisitionPolicy;
use App\Policies\FabricReturnItemPolicy;
use App\Policies\FinishingInvoicePolicy;
use App\Policies\MaterialCategoryPolicy;
use App\Policies\AssetPurchaseItemPolicy;
use App\Policies\FabricReceiveItemPolicy;
use App\Models\ServiceTransferReceiveItem;
use App\Policies\AssetPurchaseOrderPolicy;
use App\Policies\AssetReturnInvoicePolicy;
use App\Policies\FabricDistributionPolicy;
use App\Policies\FabricPurchaseItemPolicy;
use App\Policies\MaterialReturnItemPolicy;
use App\Policies\ProductRequisitionPolicy;
use App\Policies\FabricPurchaseOrderPolicy;
use App\Policies\FabricReturnInvoicePolicy;
use App\Policies\MaterialReceiveItemPolicy;
use App\Policies\ServiceTransferItemPolicy;
use App\Models\AssetDistributionReceiveItem;
use App\Policies\AssetRequisitionItemPolicy;
use App\Policies\MaterialDistributionPolicy;
use App\Policies\MaterialPurchaseItemPolicy;
use App\Policies\AssetDistributionItemPolicy;
use App\Policies\MaterialPurchaseOrderPolicy;
use App\Policies\MaterialReturnInvoicePolicy;
use App\Policies\ProductRequisitionItemPolicy;
use App\Policies\ServiceTransferInvoicePolicy;
use App\Policies\AssetDistributionInvoicePolicy;
use App\Policies\ServiceTransferReceiveItemPolicy;
use App\Policies\AssetDistributionReceiveItemPolicy;
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
        Location::class                     => LocationPolicy::class,
        Department::class                   => DepartmentPolicy::class,
        Section::class                      => SectionPolicy::class,
        SubSection::class                   => SubSectionPolicy::class,
        Designation::class                  => DesignationPolicy::class,
        Employee::class                     => EmployeePolicy::class,
        FabricCategory::class               => FabricCategoryPolicy::class,
        Fabric::class                       => FabricPolicy::class,
        FabricPurchaseOrder::class          => FabricPurchaseOrderPolicy::class,
        FabricPurchaseItem::class           => FabricPurchaseItemPolicy::class,
        FabricReceiveItem::class            => FabricReceiveItemPolicy::class,
        FabricReturnInvoice::class          => FabricReturnInvoicePolicy::class,
        FabricReturnItem::class             => FabricReturnItemPolicy::class,
        FabricDistribution::class           => FabricDistributionPolicy::class,
        MaterialCategory::class             => MaterialCategoryPolicy::class,
        Material::class                     => MaterialPolicy::class,
        MaterialPurchaseOrder::class        => MaterialPurchaseOrderPolicy::class,
        MaterialPurchaseItem::class         => MaterialPurchaseItemPolicy::class,
        MaterialReceiveItem::class          => MaterialReceiveItemPolicy::class,
        MaterialReturnInvoice::class        => MaterialReturnInvoicePolicy::class,
        MaterialReturnItem::class           => MaterialReturnItemPolicy::class,
        MaterialDistribution::class         => MaterialDistributionPolicy::class,
        AssetCategory::class                => AssetCategoryPolicy::class,
        Asset::class                        => AssetPolicy::class,
        AssetPurchaseOrder::class           => AssetPurchaseOrderPolicy::class,
        AssetPurchaseItem::class            => AssetPurchaseItemPolicy::class,
        AssetReceiveItem::class             => AssetReceiveItemPolicy::class,
        AssetReturnInvoice::class           => AssetReturnInvoicePolicy::class,
        AssetReturnItem::class              => AssetReturnItemPolicy::class,
        AssetRequisition::class             => AssetRequisitionPolicy::class,
        AssetRequisitionItem::class         => AssetRequisitionItemPolicy::class,
        AssetDistributionInvoice::class     => AssetDistributionInvoicePolicy::class,
        AssetDistributionItem::class        => AssetDistributionItemPolicy::class,
        AssetDistributionReceiveItem::class => AssetDistributionReceiveItemPolicy::class,
        AssetConsume::class                 => AssetConsumePolicy::class,
        ServiceCategory::class              => ServiceCategoryPolicy::class,
        Service::class                      => ServicePolicy::class,
        ServiceInvoice::class               => ServiceInvoicePolicy::class,
        ServiceDispatch::class              => ServiceDispatchPolicy::class,
        ServiceReceive::class               => ServiceReceivePolicy::class,
        ServiceTransferInvoice::class       => ServiceTransferInvoicePolicy::class,
        ServiceTransferItem::class          => ServiceTransferItemPolicy::class,
        ServiceTransferReceiveItem::class   => ServiceTransferReceiveItemPolicy::class,
        ProductCategory::class              => ProductCategoryPolicy::class,
        Product::class                      => ProductPolicy::class,
        ProductOutput::class                => ProductOutputPolicy::class,
        FinishingInvoice::class             => FinishingInvoicePolicy::class,
        Finishing::class                    => FinishingPolicy::class,
        ProductRequisition::class           => ProductRequisitionPolicy::class,
        ProductRequisitionItem::class       => ProductRequisitionItemPolicy::class,
        Supplier::class                     => SupplierPolicy::class,
        Expenser::class                     => ExpenserPolicy::class,
        ExpenseCategory::class              => ExpenseCategoryPolicy::class,
        Expense::class                      => ExpensePolicy::class,
        Balance::class                      => BalancePolicy::class,
        Provider::class                     => ProviderPolicy::class,
        Permission::class                   => PermissionPolicy::class,
        Role::class                         => RolePolicy::class,
        User::class                         => UserPolicy::class,
        Floor::class                        => FloorPolicy::class,
        Style::class                        => StylePolicy::class,
        Unit::class                         => UnitPolicy::class,
        Activity::class                     => ActivityLogPolicy::class,
        Setting::class                      => SettingPolicy::class,
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
