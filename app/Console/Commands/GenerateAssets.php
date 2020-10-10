<?php

namespace App\Console\Commands;

use App\Models\Asset;
use App\Models\Location;
use Illuminate\Console\Command;

class GenerateAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $locations = Location::where('id', '!=', 1)->get();

        foreach($locations as $location)
        {
            $assets = Asset::all();
            $count = 1;

            foreach($assets as $asset){
                $location->assets()->updateOrCreate(
                    [
                        'location_id' => $location->id,
                        'code'        => $asset->code,
                    ],

                    [
                    'name' => $asset->name,
                    'code' => $asset->code,
                    'description' => $asset->description,
                    'rate' => $asset->rate,
                    'opening_quantity' => 0,
                    'quantity' => 0,
                    'alert_quantity' => 0,
                    'unit_id' => $asset->unit_id,
                    ]
                );

                $this->info("SL-{$count}, {$asset->name} created/updated for {$location->name}");

                $count++;
            }
        }
    }
}
