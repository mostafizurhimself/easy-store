<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceDispatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_dispatches', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('service_id')->unsigned();
            $table->bigInteger('invoice_id')->unsigned()->nullable();
            $table->double('dispatch_quantity')->default(0);
            $table->double('receive_quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('dispatch_amount')->default(0);
            $table->double('receive_amount')->default(0);
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('invoice_id')->references('id')->on('service_invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_dispatches');
    }
}
