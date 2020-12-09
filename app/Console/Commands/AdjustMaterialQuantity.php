<?php

namespace App\Console\Commands;

use App\Models\Material;
use Illuminate\Console\Command;

class AdjustMaterialQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust:material {--id=*} {--all}';

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
        // dd($this->options());
        if ($this->option('all')) {
            $materials = Material::all();

            foreach ($materials as $material) {
                $material->quantity = ($material->opening_quantity +
                    $material->receiveItems()->where('status', 'confirmed')->sum('quantity') +
                    $material->transferReceiveItems()->where('status', 'confirmed')->sum('quantity'))
                    - ($material->distributions()->where('status', 'confirmed')->sum('quantity') +
                        $material->returnItems()->where('status', 'confirmed')->sum('quantity') +
                        $material->transferItems()->where('status', 'confirmed')->sum('transfer_quantity'))
                    + $material->adjustQuantities()->sum('quantity');

                $material->save();
                $this->info("Material Quantity Adjusted ($material->id - $material->name)");
            }
        }else{
            $materials = Material::whereIn('id', $this->option('id'))->get();

            foreach ($materials as $material) {
                $material->quantity = ($material->opening_quantity +
                    $material->receiveItems()->where('status', 'confirmed')->sum('quantity') +
                    $material->transferReceiveItems()->where('status', 'confirmed')->sum('quantity'))
                    - ($material->distributions()->where('status', 'confirmed')->sum('quantity') +
                        $material->returnItems()->where('status', 'confirmed')->sum('quantity') +
                        $material->transferItems()->where('status', 'confirmed')->sum('transfer_quantity'))
                    + $material->adjustQuantities()->sum('quantity');

                $material->save();
                $this->info("Material Quantity Adjusted ($material->id - $material->name)");
            }
        }
    }
}
