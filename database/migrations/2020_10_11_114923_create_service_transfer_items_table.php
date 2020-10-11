<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTransferItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('service_id')->unsigned();
            $table->bigInteger('invoice_id')->unsigned();
            $table->double('transfer_quantity')->default(0);
            $table->double('receive_quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('transfer_amount')->default(0);
            $table->double('receive_amount')->default(0);
            $table->text('description')->nullable();
            $table->bigInteger('unit_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('invoice_id')->references('id')->on('service_transfer_invoices');
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
        Schema::dropIfExists('service_transfer_items');
    }
}
