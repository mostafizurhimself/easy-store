<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricTransferInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_transfer_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('fabric_transfer_invoice_no_index');
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->date('date')->index('fabric_transfer_invoice_date_index');
            $table->double('total_transfer_amount')->default(0);
            $table->double('total_receive_amount')->default(0);
            $table->text('note')->nullable();
            $table->bigInteger('receiver_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('receiver_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fabric_transfer_invoices');
    }
}
