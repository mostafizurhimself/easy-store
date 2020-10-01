<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Fabric;
use App\Models\Product;
use App\Models\Service;
use App\Models\Employee;
use App\Models\Expenser;
use App\Models\Material;
use App\Models\Provider;
use App\Models\Supplier;
use App\Models\Finishing;
use App\Models\ProductOutput;
use App\Models\ServiceReceive;
use App\Models\AssetReturnItem;
use App\Models\ServiceDispatch;
use App\Models\AssetReceiveItem;
use App\Models\FabricReturnItem;
use App\Observers\AssetObserver;
use App\Models\AssetPurchaseItem;
use App\Models\FabricReceiveItem;
use App\Observers\FabricObserver;
use App\Models\FabricDistribution;
use App\Models\FabricPurchaseItem;
use App\Models\MaterialReturnItem;
use App\Observers\ProductObserver;
use App\Observers\ServiceObserver;
use App\Models\FabricReturnInvoice;
use App\Models\MaterialReceiveItem;
use App\Observers\EmployeeObserver;
use App\Observers\ExpenserObserver;
use App\Observers\MaterialObserver;
use App\Observers\ProviderObserver;
use App\Observers\SupplierObserver;
use App\Models\AssetRequisitionItem;
use App\Models\MaterialDistribution;
use App\Models\MaterialPurchaseItem;
use App\Observers\FinishingObserver;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetDistributionItem;
use Illuminate\Support\ServiceProvider;
use App\Observers\ProductOutputObserver;
use App\Observers\ServiceReceiveObserver;
use App\Observers\AssetReturnItemObserver;
use App\Observers\ServiceDispatchObserver;
use App\Observers\AssetReceiveItemObserver;
use App\Observers\FabricReturnItemObserver;
use App\Models\AssetDistributionReceiveItem;
use App\Observers\AssetPurchaseItemObserver;
use App\Observers\FabricReceiveItemObserver;
use App\Observers\FabricDistributionObserver;
use App\Observers\FabricPurchaseItemObserver;
use App\Observers\MaterialReturnItemObserver;
use App\Observers\FabricReturnInvoiceObserver;
use App\Observers\MaterialReceiveItemObserver;
use App\Observers\AssetRequisitionItemObserver;
use App\Observers\MaterialDistributionObserver;
use App\Observers\MaterialPurchaseItemObserver;
use App\Observers\AssetDistributionItemObserver;
use App\Observers\AssetDistributionReceiveItemObserver;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Employee::observe(EmployeeObserver::class);
        Fabric::observe(FabricObserver::class);
        Material::observe(MaterialObserver::class);
        Asset::observe(AssetObserver::class);
        Supplier::observe(SupplierObserver::class);
        Provider::observe(ProviderObserver::class);
        FabricPurchaseItem::observe(FabricPurchaseItemObserver::class);
        FabricReceiveItem::observe(FabricReceiveItemObserver::class);
        FabricReturnItem::observe(FabricReturnItemObserver::class);
        FabricDistribution::observe(FabricDistributionObserver::class);
        MaterialPurchaseItem::observe(MaterialPurchaseItemObserver::class);
        MaterialReceiveItem::observe(MaterialReceiveItemObserver::class);
        MaterialReturnItem::observe(MaterialReturnItemObserver::class);
        MaterialDistribution::observe(MaterialDistributionObserver::class);
        AssetPurchaseItem::observe(AssetPurchaseItemObserver::class);
        AssetReceiveItem::observe(AssetReceiveItemObserver::class);
        AssetReturnItem::observe(AssetReturnItemObserver::class);
        AssetRequisitionItem::observe(AssetRequisitionItemObserver::class);
        AssetDistributionItem::observe(AssetDistributionItemObserver::class);
        AssetDistributionReceiveItem::observe(AssetDistributionReceiveItemObserver::class);
        Service::observe(ServiceObserver::class);
        ServiceDispatch::observe(ServiceDispatchObserver::class);
        ServiceReceive::observe(ServiceReceiveObserver::class);
        Product::observe(ProductObserver::class);
        ProductOutput::observe(ProductOutputObserver::class);
        Finishing::observe(FinishingObserver::class);
        Expenser::observe(ExpenserObserver::class);
    }
}
