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
use Easystore\ProfileTool\ProfileTool;
use Coroowicaksono\NovaCarousel\Slider;
use App\Nova\Metrics\DailyProductOutput;
use App\Nova\Metrics\TotalAssetPurchase;
use App\Nova\Metrics\TotalPurchaseOrder;
use App\Nova\Metrics\TotalFabricPurchase;
use App\Nova\Dashboards\GarmentsDashboard;
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
            // Line 1
            new \Richardkeep\NovaTimenow\NovaTimenow,


            // Line 2
            new \GijsG\SystemResources\SystemResources('ram', "1/3"),
            new \GijsG\SystemResources\SystemResources('cpu', "1/3"),

        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            (new GarmentsDashboard())->canSee(function ($request) {
                return $request->user()->hasPermissionTo('view garments dashboard') || $request->user()->isSuperAdmin();
            })
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            (\Eminiarts\NovaPermissions\NovaPermissions::make())->canSee(function () {
                return false;
            })
                ->roleResource(Role::class)
                ->permissionResource(Permission::class),

            \ChrisWare\NovaBreadcrumbs\NovaBreadcrumbs::make()->withoutStyles(),

            \Mirovit\NovaNotifications\NovaNotifications::make(),

            ProfileTool::make(),
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
