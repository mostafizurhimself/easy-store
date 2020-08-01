<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialPurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('material_pi_number_index');
            $table->bigInteger('purchase_order_id')->unsigned();
            $table->bigInteger('material_id')->unsigned();
            $table->double('purchase_quantity')->default(0);
            $table->double('receive_quantity')->default(0);
            $table->double('purchase_rate')->default(0);
            $table->double('purchase_amount')->default(0);
            $table->double('receive_amount')->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('purchase_order_id')->references('id')->on('material_purchase_orders');
            $table->foreign('material_id')->references('id')->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_purchase_items');
    }
}
