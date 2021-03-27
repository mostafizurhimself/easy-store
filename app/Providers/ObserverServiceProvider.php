<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Fabric;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Service;
use App\Models\Employee;
use App\Models\Expenser;
use App\Models\Material;
use App\Models\Provider;
use App\Models\Supplier;
use App\Models\Finishing;
use App\Models\Attendance;
use App\Models\GoodsGatePass;
use App\Models\ProductOutput;
use App\Models\ManualGatePass;
use App\Models\ServiceInvoice;
use App\Models\ServiceReceive;
use App\Models\AssetReturnItem;
use App\Models\ServiceDispatch;
use App\Models\AssetReceiveItem;
use App\Models\AssetRequisition;
use App\Models\FabricReturnItem;
use App\Models\FinishingInvoice;
use App\Observers\AssetObserver;
use App\Models\AssetPurchaseItem;
use App\Models\FabricReceiveItem;
use App\Observers\FabricObserver;
use App\Models\AssetPurchaseOrder;
use App\Models\AssetReturnInvoice;
use App\Models\FabricDistribution;
use App\Models\FabricPurchaseItem;
use App\Models\FabricTransferItem;
use App\Models\MaterialReturnItem;
use App\Models\ProductRequisition;
use App\Observers\ExpenseObserver;
use App\Observers\ProductObserver;
use App\Observers\ServiceObserver;
use App\Models\FabricPurchaseOrder;
use App\Models\FabricReturnInvoice;
use App\Models\MaterialReceiveItem;
use App\Models\ServiceTransferItem;
use App\Observers\EmployeeObserver;
use App\Observers\ExpenserObserver;
use App\Observers\MaterialObserver;
use App\Observers\ProviderObserver;
use App\Observers\SupplierObserver;
use App\Models\AssetRequisitionItem;
use App\Models\MaterialDistribution;
use App\Models\MaterialPurchaseItem;
use App\Models\MaterialTransferItem;
use App\Observers\FinishingObserver;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetDistributionItem;
use App\Models\FabricTransferInvoice;
use App\Models\MaterialPurchaseOrder;
use App\Models\MaterialReturnInvoice;
use App\Observers\AttendanceObserver;
use App\Models\ProductRequisitionItem;
use App\Models\ServiceTransferInvoice;
use App\Models\MaterialTransferInvoice;
use Illuminate\Support\ServiceProvider;
use App\Models\AssetDistributionInvoice;
use App\Observers\GoodsGatePassObserver;
use App\Observers\ProductOutputObserver;
use App\Models\FabricTransferReceiveItem;
use App\Observers\ManualGatePassObserver;
use App\Observers\ServiceInvoiceObserver;
use App\Observers\ServiceReceiveObserver;
use App\Models\ServiceTransferReceiveItem;
use App\Observers\AssetReturnItemObserver;
use App\Observers\ServiceDispatchObserver;
use App\Models\MaterialTransferReceiveItem;
use App\Observers\AssetReceiveItemObserver;
use App\Observers\AssetRequisitionObserver;
use App\Observers\FabricReturnItemObserver;
use App\Observers\FinishingInvoiceObserver;
use App\Models\AssetDistributionReceiveItem;
use App\Observers\AssetPurchaseItemObserver;
use App\Observers\FabricReceiveItemObserver;
use App\Observers\AssetPurchaseOrderObserver;
use App\Observers\AssetReturnInvoiceObserver;
use App\Observers\FabricDistributionObserver;
use App\Observers\FabricPurchaseItemObserver;
use App\Observers\FabricTransferItemObserver;
use App\Observers\MaterialReturnItemObserver;
use App\Observers\ProductRequisitionObserver;
use App\Observers\FabricPurchaseOrderObserver;
use App\Observers\FabricReturnInvoiceObserver;
use App\Observers\MaterialReceiveItemObserver;
use App\Observers\ServiceTransferItemObserver;
use App\Observers\AssetRequisitionItemObserver;
use App\Observers\MaterialDistributionObserver;
use App\Observers\MaterialPurchaseItemObserver;
use App\Observers\MaterialTransferItemObserver;
use App\Observers\AssetDistributionItemObserver;
use App\Observers\FabricTransferInvoiceObserver;
use App\Observers\MaterialPurchaseOrderObserver;
use App\Observers\MaterialReturnInvoiceObserver;
use App\Observers\ProductRequisitionItemObserver;
use App\Observers\ServiceTransferInvoiceObserver;
use App\Observers\MaterialTransferInvoiceObserver;
use App\Observers\AssetDistributionInvoiceObserver;
use App\Observers\FabricTransferReceiveItemObserver;
use App\Observers\ServiceTransferReceiveItemObserver;
use App\Observers\MaterialTransferReceiveItemObserver;
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
        Attendance::observe(AttendanceObserver::class);
        Fabric::observe(FabricObserver::class);
        Material::observe(MaterialObserver::class);
        Asset::observe(AssetObserver::class);
        Supplier::observe(SupplierObserver::class);
        Provider::observe(ProviderObserver::class);
        FabricPurchaseOrder::observe(FabricPurchaseOrderObserver::class);
        FabricPurchaseItem::observe(FabricPurchaseItemObserver::class);
        FabricReceiveItem::observe(FabricReceiveItemObserver::class);
        FabricReturnInvoice::observe(FabricReturnInvoiceObserver::class);
        FabricReturnItem::observe(FabricReturnItemObserver::class);
        FabricTransferInvoice::observe(FabricTransferInvoiceObserver::class);
        FabricTransferItem::observe(FabricTransferItemObserver::class);
        FabricTransferReceiveItem::observe(FabricTransferReceiveItemObserver::class);
        FabricDistribution::observe(FabricDistributionObserver::class);
        MaterialPurchaseOrder::observe(MaterialPurchaseOrderObserver::class);
        MaterialPurchaseItem::observe(MaterialPurchaseItemObserver::class);
        MaterialReceiveItem::observe(MaterialReceiveItemObserver::class);
        MaterialReturnInvoice::observe(MaterialReturnInvoiceObserver::class);
        MaterialReturnItem::observe(MaterialReturnItemObserver::class);
        MaterialTransferInvoice::observe(MaterialTransferInvoiceObserver::class);
        MaterialTransferItem::observe(MaterialTransferItemObserver::class);
        MaterialTransferReceiveItem::observe(MaterialTransferReceiveItemObserver::class);
        MaterialDistribution::observe(MaterialDistributionObserver::class);
        AssetPurchaseOrder::observe(AssetPurchaseOrderObserver::class);
        AssetPurchaseItem::observe(AssetPurchaseItemObserver::class);
        AssetReceiveItem::observe(AssetReceiveItemObserver::class);
        AssetReturnInvoice::observe(AssetReturnInvoiceObserver::class);
        AssetReturnItem::observe(AssetReturnItemObserver::class);
        AssetRequisition::observe(AssetRequisitionObserver::class);
        AssetRequisitionItem::observe(AssetRequisitionItemObserver::class);
        AssetDistributionInvoice::observe(AssetDistributionInvoiceObserver::class);
        AssetDistributionItem::observe(AssetDistributionItemObserver::class);
        AssetDistributionReceiveItem::observe(AssetDistributionReceiveItemObserver::class);
        Service::observe(ServiceObserver::class);
        ServiceInvoice::observe(ServiceInvoiceObserver::class);
        ServiceDispatch::observe(ServiceDispatchObserver::class);
        ServiceReceive::observe(ServiceReceiveObserver::class);
        ServiceTransferInvoice::observe(ServiceTransferInvoiceObserver::class);
        ServiceTransferItem::observe(ServiceTransferItemObserver::class);
        ServiceTransferReceiveItem::observe(ServiceTransferReceiveItemObserver::class);
        Product::observe(ProductObserver::class);
        ProductOutput::observe(ProductOutputObserver::class);
        FinishingInvoice::observe(FinishingInvoiceObserver::class);
        Finishing::observe(FinishingObserver::class);
        ProductRequisition::observe(ProductRequisitionObserver::class);
        ProductRequisitionItem::observe(ProductRequisitionItemObserver::class);
        Expenser::observe(ExpenserObserver::class);
        Expense::observe(ExpenseObserver::class);
        GoodsGatePass::observe(GoodsGatePassObserver::class);
        ManualGatePass::observe(ManualGatePassObserver::class);
    }
}
