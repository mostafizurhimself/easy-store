<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetRequisitionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('asset_requisition_item_no_index');
            $table->bigInteger('requisition_id')->unsigned();
            $table->bigInteger('asset_id')->unsigned();
            $table->double('requisition_quantity')->default(0);
            $table->double('distribution_quantity')->default(0);
            $table->double('requisition_rate')->default(0);
            $table->double('requisition_amount')->default(0);
            $table->double('distribution_amount')->default(0);
            $table->bigInteger('unit_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('requisition_id')->references('id')->on('asset_requisitions');
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
        Schema::dropIfExists('asset_requisition_items');
    }
}
