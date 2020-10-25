<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricTransferReceiveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_transfer_receive_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('fabric_transfer_receive_item_no_index');
            $table->date('date');
            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('fabric_id')->unsigned();
            $table->bigInteger('transfer_item_id')->unsigned();
            $table->string('reference')->nullable()->index('fabric_transfer_receive_reference_index');
            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('amount')->default(0);
            $table->text('note')->nullable();
            $table->bigInteger('unit_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('invoice_id')->references('id')->on('fabric_transfer_invoices');
            $table->foreign('fabric_id')->references('id')->on('fabrics');
            $table->foreign('transfer_item_id')->references('id')->on('fabric_transfer_items');
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
        Schema::dropIfExists('fabric_transfer_receive_items');
    }
}
