<?php

namespace App\Providers;

use App\Models\User;
use App\Nova\Role;
use Laravel\Nova\Nova;
use App\Nova\Permission;
use Illuminate\Http\Request;
use Laravel\Nova\Cards\Help;
use App\Nova\Metrics\ActiveUser;
use App\Nova\Metrics\TotalShowroom;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Events\ServingNova;
use Easystore\ProfileTool\ProfileTool;
use Easystore\ScanGatepass\ScanGatepass;
use App\Nova\Dashboards\GarmentsDashboard;
use Easystore\ScanAttendance\ScanAttendance;
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

        // Force HTTPS
        URL::forceScheme('https');

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
            // return in_array($user->email, [

            // ]);
            return true;
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
            new TotalShowroom(),
            new ActiveUser,


            // Line 2
            new \GijsG\SystemResources\SystemResources('ram', "1/2"),
            new \GijsG\SystemResources\SystemResources('cpu', "1/2"),

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

            ScanGatepass::make(),

            ScanAttendance::make(),
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