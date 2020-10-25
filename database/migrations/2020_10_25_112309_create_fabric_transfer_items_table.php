<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricTransferItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('fabric_transfer_item_no_index');
            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('fabric_id')->unsigned();
            $table->double('transfer_quantity')->default(0);
            $table->double('receive_quantity')->default(0);
            $table->double('transfer_rate')->default(0);
            $table->double('transfer_amount')->default(0);
            $table->double('receive_amount')->default(0);
            $table->bigInteger('unit_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('invoice_id')->references('id')->on('fabric_transfer_invoices');
            $table->foreign('fabric_id')->references('id')->on('fabrics');
            $table->foreign('unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fabric_transfer_items');
    }
}
