<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricPurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('purchase_order_id')->unsigned();
            $table->bigInteger('fabric_id')->unsigned();
            $table->double('purchase_quantity')->default(0);
            $table->double('receive_quantity')->default(0);
            $table->double('purchase_rate')->default(0);
            $table->double('purchase_amount')->default(0);
            $table->double('receive_amount')->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('purchase_order_id')->references('id')->on('fabric_purchase_orders');
            $table->foreign('fabric_id')->references('id')->on('fabrics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_item_fabrics');
    }
}
