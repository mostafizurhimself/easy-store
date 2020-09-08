<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('fabric_po_number_index');;
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->date('date')->index('purchase_fabric_date_index');
            $table->bigInteger('supplier_id')->unsigned();
            $table->double('total_purchase_amount')->default(0);
            $table->double('total_receive_amount')->default(0);
            $table->text('note')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_fabrics');
    }
}
