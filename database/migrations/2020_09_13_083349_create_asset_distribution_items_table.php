<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetDistributionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_distribution_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('asset_distribution_item_no_index');
            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('requisition_id')->unsigned()->nullable();
            $table->bigInteger('requisition_item_id')->unsigned()->nullable();
            $table->bigInteger('asset_id')->unsigned();
            $table->double('distribution_quantity')->default(0);
            $table->double('receive_quantity')->default(0);
            $table->double('distribution_rate')->default(0);
            $table->double('distribution_amount')->default(0);
            $table->double('receive_amount')->default(0);
            $table->bigInteger('unit_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('invoice_id')->references('id')->on('asset_distribution_invoices');
            $table->foreign('requisition_id')->references('id')->on('asset_requisitions');
            $table->foreign('requisition_item_id')->references('id')->on('asset_requisition_items');
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
        Schema::dropIfExists('asset_distribution_items');
    }
}
