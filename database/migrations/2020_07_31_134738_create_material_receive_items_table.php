<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialReceiveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_receive_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('material_ri_number_index');
            $table->date('date');
            $table->bigInteger('purchase_order_id')->unsigned();
            $table->bigInteger('material_id')->unsigned();
            $table->bigInteger('purchase_item_id')->unsigned();
            $table->string('reference')->nullable();
            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('amount')->default(0);
            $table->text('note')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('purchase_order_id')->references('id')->on('material_purchase_orders');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('purchase_item_id')->references('id')->on('material_purchase_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_receive_items');
    }
}
