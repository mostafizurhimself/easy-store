<?php

namespace App\Console\Commands;

use App\Models\Fabric;
use App\Models\Location;
use Illuminate\Console\Command;

class GenerateFabrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:fabrics {--id= : The id of the location }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do not run this command.';

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
        $locationId = $this->option('id');

        if(empty($locationId)){
            $locations = Location::where('id', '!=', 1)->get();
        }else{
            $locations = Location::where('id', $locationId)->get();
        }

        foreach($locations as $location)
        {
            $fabrics = Fabric::where('location_id', 1)->get();
            $count = 1;

            foreach($fabrics as $fabric){
                $location->fabrics()->updateOrCreate(
                    [
                        'location_id' => $location->id,
                        'code'        => $fabric->code,
                    ],

                    [
                        'name' => $fabric->name,
                        'code' => $fabric->code,
                        'description' => $fabric->description,
                        'rate' => $fabric->rate,
                        'opening_quantity' => 0,
                        'quantity' => 0,
                        'alert_quantity' => 0,
                        'unit_id' => $fabric->unit_id,
                    ]
                );

                $this->info("SL-{$count}, {$fabric->name} created/updated for {$location->name}");

                $count++;
            }
        }
    }
}
