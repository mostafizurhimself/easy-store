<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetPurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('asset_pi_number_index');
            $table->bigInteger('purchase_order_id')->unsigned();
            $table->bigInteger('asset_id')->unsigned();
            $table->double('purchase_quantity')->default(0);
            $table->double('receive_quantity')->default(0);
            $table->double('purchase_rate')->default(0);
            $table->double('purchase_amount')->default(0);
            $table->double('receive_amount')->default(0);
            $table->string('status')->default('draft');
            $table->bigInteger('unit_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('purchase_order_id')->references('id')->on('asset_purchase_orders');
            $table->foreign('asset_id')->references('id')->on('assets');
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
        Schema::dropIfExists('asset_purchase_items');
    }
}
