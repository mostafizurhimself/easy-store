<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricReceiveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_receive_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->date('date');
            $table->bigInteger('purchase_order_id')->unsigned();
            $table->bigInteger('fabric_id')->unsigned();
            $table->bigInteger('purchase_item_id')->unsigned();
            $table->string('reference')->nullable();
            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('amount')->default(0);
            $table->text('note')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('purchase_order_id')->references('id')->on('fabric_purchase_orders');
            $table->foreign('fabric_id')->references('id')->on('fabrics');
            $table->foreign('purchase_item_id')->references('id')->on('fabric_purchase_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receive_item_fabrics');
    }
}
