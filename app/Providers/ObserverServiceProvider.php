<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Fabric;
use App\Models\Employee;
use App\Models\Material;
use App\Models\Provider;
use App\Models\Supplier;
use App\Observers\AssetObserver;
use App\Observers\FabricObserver;
use App\Models\FabricReceiveItem;
use App\Models\FabricPurchaseItem;
use App\Observers\EmployeeObserver;
use App\Observers\MaterialObserver;
use App\Observers\ProviderObserver;
use App\Observers\SupplierObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\FabricReceiveItemObserver;
use App\Observers\FabricPurchaseItemObserver;

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
    }
}
