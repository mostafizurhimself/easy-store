<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Fabric;
use App\Models\Product;
use App\Models\Service;
use App\Models\Employee;
use App\Models\Material;
use App\Models\Provider;
use App\Models\Supplier;
use App\Models\Finishing;
use App\Models\ProductOutput;
use App\Models\ServiceDispatch;
use App\Models\AssetReceiveItem;
use App\Observers\AssetObserver;
use App\Models\AssetPurchaseItem;
use App\Models\AssetTransferItem;
use App\Models\FabricReceiveItem;
use App\Observers\FabricObserver;
use App\Models\FabricDistribution;
use App\Models\FabricPurchaseItem;
use App\Observers\ProductObserver;
use App\Observers\ServiceObserver;
use App\Models\MaterialReceiveItem;
use App\Observers\EmployeeObserver;
use App\Observers\MaterialObserver;
use App\Observers\ProviderObserver;
use App\Observers\SupplierObserver;
use App\Models\MaterialPurchaseItem;
use App\Observers\FinishingObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\ProductOutputObserver;
use App\Observers\ServiceDispatchObserver;
use App\Observers\AssetReceiveItemObserver;
use App\Observers\AssetPurchaseItemObserver;
use App\Observers\AssetTransferItemObserver;
use App\Observers\FabricReceiveItemObserver;
use App\Observers\FabricDistributionObserver;
use App\Observers\FabricPurchaseItemObserver;
use App\Observers\MaterialReceiveItemObserver;
use App\Observers\MaterialPurchaseItemObserver;

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
        FabricDistribution::observe(FabricDistributionObserver::class);
        MaterialPurchaseItem::observe(MaterialPurchaseItemObserver::class);
        MaterialReceiveItem::observe(MaterialReceiveItemObserver::class);
        AssetPurchaseItem::observe(AssetPurchaseItemObserver::class);
        AssetReceiveItem::observe(AssetReceiveItemObserver::class);
        AssetTransferItem::observe(AssetTransferItemObserver::class);
        Service::observe(ServiceObserver::class);
        ServiceDispatch::observe(ServiceDispatchObserver::class);
        Product::observe(ProductObserver::class);
        ProductOutput::observe(ProductOutputObserver::class);
        Finishing::observe(FinishingObserver::class);
    }
}
