<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Console\Command;

class RemovePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:permissions {permissions*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove listed permissions from the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $permissions = $this->argument('permissions');

        foreach ($permissions as $permission) {
            Permission::where('name', $permission)->first()->delete();
            $this->info("Permission: $permission is removed successfully.");
        }

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
