<?php

namespace App\Providers;

use App\Nova\Role;
use App\Nova\Location;
use Laravel\Nova\Nova;
use App\Nova\Department;
use App\Nova\Permission;
use Illuminate\Http\Request;
use Laravel\Nova\Cards\Help;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Events\ServingNova;
use App\Nova\Metrics\DailyProductOutput;
use App\Nova\Metrics\TotalAssetPurchase;
use App\Nova\Metrics\TotalPurchaseOrder;
use App\Nova\Metrics\TotalFabricPurchase;
use App\Nova\Metrics\TotalServiceDispatch;
use App\Nova\Metrics\DailyProductFinishing;
use App\Nova\Metrics\TotalMaterialPurchase;
use Coroowicaksono\ChartJsIntegration\LineChart;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //Sort the resources by its priority property
        Nova::sortResourcesBy(function ($resource) {
            return $resource::$priority ?? 9999;
        });

        Nova::userTimezone(function (Request $request) {
            return $request->user()->timezone;
        });
    }



    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
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
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            (\Eminiarts\NovaPermissions\NovaPermissions::make())->canSee(function(){
                return false;
            })
            ->roleResource(Role::class)
            ->permissionResource(Permission::class),

            \ChrisWare\NovaBreadcrumbs\NovaBreadcrumbs::make()->withoutStyles(),

            \Mirovit\NovaNotifications\NovaNotifications::make(),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
