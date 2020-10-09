<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetReceiveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_receive_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('asset_ri_number_index');
            $table->date('date');
            $table->bigInteger('purchase_order_id')->unsigned();
            $table->bigInteger('asset_id')->unsigned();
            $table->bigInteger('purchase_item_id')->unsigned();
            $table->string('reference')->nullable()->index('asset_receive_reference_index');
            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('amount')->default(0);
            $table->text('note')->nullable();
            $table->bigInteger('unit_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('purchase_order_id')->references('id')->on('asset_purchase_orders');
            $table->foreign('asset_id')->references('id')->on('assets');
            $table->foreign('purchase_item_id')->references('id')->on('asset_purchase_items');
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
        Schema::dropIfExists('asset_receive_items');
    }
}
