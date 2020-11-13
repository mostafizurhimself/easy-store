<?php

namespace App\Console\Commands;

use App\Models\AdjustQuantity;
use Illuminate\Console\Command;

class AdjustTableAdjustment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust:adjust-table';

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
        $rows = AdjustQuantity::all();

        foreach($rows as $row){
            if($row->type == 'decrement'){
                $row->quantity  = $row->quantity - ($row->quantity * 2);
                $row->save();
                $this->info("Quantity adjusted to {$row->quantity}");
            }
        }

    }
}
