<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRequisitionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('product_requisition_item_no_index');
            $table->bigInteger('requisition_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->double('requisition_quantity')->default(0);
            $table->double('requisition_rate')->default(0);
            $table->double('requisition_amount')->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('requisition_id')->references('id')->on('product_requisitions');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_requisition_items');
    }
}
