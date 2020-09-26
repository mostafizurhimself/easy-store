<?php

namespace App\Providers;

use App\Nova\Department;
use App\Nova\Role;
use Laravel\Nova\Nova;
use App\Nova\Location;
use App\Nova\Permission;
use Laravel\Nova\Cards\Help;
use Illuminate\Support\Facades\Gate;
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
            new Help,
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

            \EasyStore\PreventDirectCreateTool\PreventDirectCreateTool::make(),

            \Easystore\PurchaseInvoiceTool\PurchaseInvoiceTool::make()
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
