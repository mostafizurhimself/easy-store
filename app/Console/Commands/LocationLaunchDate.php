<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class LocationLaunchDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:launch-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add launch date of location';

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
        $locations = Location::all();

        foreach($locations as $location){
            if(empty($location->launchDate)){
                $location->launchDate = $location->createdAt;
                $location->save();
                $this->info("Launch date added to {$location->name}");
            }
        }
    }
}
