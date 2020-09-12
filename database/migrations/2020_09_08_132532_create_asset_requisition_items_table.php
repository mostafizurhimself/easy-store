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
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
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
