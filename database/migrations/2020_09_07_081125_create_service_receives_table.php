<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_receives', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('service_receive_number_index');
            $table->date('date');
            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('dispatch_id')->unsigned();
            $table->bigInteger('service_id')->unsigned();
            $table->string('reference')->nullable()->index('service_receive_reference_index');
            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('amount')->default(0);
            $table->text('note')->nullable();
            $table->bigInteger('unit_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('invoice_id')->references('id')->on('service_invoices');
            $table->foreign('dispatch_id')->references('id')->on('service_dispatches');
            $table->foreign('service_id')->references('id')->on('services');
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
        Schema::dropIfExists('service_receives');
    }
}
