<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class MakeSystemAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:system-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is create a system admin for the system.';

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

        // Get the inputs
        $email    = $this->ask('Please input email');


        // Create super admin
        $user = User::where('email', $email)->first();

        if(empty($user)){
            return $this->error('No user found');
        }

        $user->assignRole(Role::SYSTEM_ADMIN);

        $this->info('System Admin role added successfully.');

        return 0;
    }
}
