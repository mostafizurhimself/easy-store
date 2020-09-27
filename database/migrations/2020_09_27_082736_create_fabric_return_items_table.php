<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_return_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('fabric_return_item_number_index');
            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('fabric_id')->unsigned();
            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('amount')->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('invoice_id')->references('id')->on('fabric_return_invoices');
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
        Schema::dropIfExists('fabric_return_items');
    }
}
