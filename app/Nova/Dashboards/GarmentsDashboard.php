<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboard;
use App\Nova\Metrics\DailyProductOutput;
use App\Nova\Metrics\TotalAssetPurchase;
use App\Nova\Metrics\TotalPurchaseOrder;
use App\Nova\Metrics\TotalFabricPurchase;
use App\Nova\Metrics\TotalServiceDispatch;
use App\Nova\Metrics\DailyProductFinishing;
use App\Nova\Metrics\TotalMaterialPurchase;
use Coroowicaksono\ChartJsIntegration\LineChart;

class GarmentsDashboard extends Dashboard
{
     /**
     * The icon of the dashboard.
     *
     * @return string
     */
    public static function icon()
    {
        return 'fas fa-industry';
    }

    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return "Garments";
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
             // Line 1,
             new TotalFabricPurchase(),
             new TotalMaterialPurchase(),
             new TotalAssetPurchase(),

             // Line 2
             (new LineChart())
                 ->title('Purhcase Graph')
                 ->animations([
                     'enabled' => true,
                     'easing' => 'easeinout',
                 ])
                 ->series(array([
                     'barPercentage' => 0.5,
                     'label' => 'Fabric Purchase',
                     'borderColor' => '#f7a35c',
                     'data' => [80, 90, 80, 40, 62, 79, 79, 90, 90, 90, 92, 91],
                 ],
                 [
                     'barPercentage' => 0.5,
                     'label' => 'Material Purchase',
                     'borderColor' => '#90ed7d',
                     'data' => [90, 80, 40, 22, 79, 129, 90, 150, 90, 92, 91, 80],
                 ],
                 [
                     'barPercentage' => 0.5,
                     'label' => 'Asset Purchase',
                     'borderColor' => '#03a9f4',
                     'data' => [80, 30, 50, 80, 129, 50, 30, 50, 100, 102, 81, 90],
                 ]
                 ))
                 ->options([
                     'xaxis' => [
                         'categories' => [ 'Jan', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ]
                     ],
                 ])
                 ->width('2/3'),

             new TotalPurchaseOrder(),

             // Line 3
             new DailyProductOutput(),
             new DailyProductFinishing(),
             new TotalServiceDispatch(),
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'garments-dashboard';
    }
}
