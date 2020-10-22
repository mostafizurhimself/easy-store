<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialTransferItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('material_transfer_item_no_index');
            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('material_id')->unsigned();
            $table->double('transfer_quantity')->default(0);
            $table->double('receive_quantity')->default(0);
            $table->double('transfer_rate')->default(0);
            $table->double('transfer_amount')->default(0);
            $table->double('receive_amount')->default(0);
            $table->bigInteger('unit_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('invoice_id')->references('id')->on('material_transfer_invoices');
            $table->foreign('material_id')->references('id')->on('materials');
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
        Schema::dropIfExists('material_transfer_items');
    }
}
