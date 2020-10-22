<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialTransferReceiveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_transfer_receive_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('material_transfer_receive_item_no_index');
            $table->date('date');
            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('material_id')->unsigned();
            $table->bigInteger('transfer_item_id')->unsigned();
            $table->string('reference')->nullable()->index('material_transfer_receive_reference_index');
            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('amount')->default(0);
            $table->text('note')->nullable();
            $table->bigInteger('unit_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('invoice_id')->references('id')->on('material_transfer_invoices');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('transfer_item_id')->references('id')->on('material_transfer_items');
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
        Schema::dropIfExists('material_transfer_receive_items');
    }
}
