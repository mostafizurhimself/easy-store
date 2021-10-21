<?php

namespace App\Console\Commands;

use App\Models\ServiceInvoice;
use Illuminate\Console\Command;

class RecalculateServiceInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recalculate:service-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculates service invoices dispatch and receive quantity';

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
        $invoices = ServiceInvoice::all();

        foreach ($invoices as $invoice) {
            $invoice->totalDispatchQuantity = $invoice->dispatches()->sum('dispatch_quantity');
            $invoice->totalReceiveQuantity = $invoice->receives()->sum('quantity');
            $invoice->save();
            $this->info("Service Invoice {$invoice->readableId} calculated successfully");
        }
    }
}