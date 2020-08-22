<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceDispatchInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_dispatch_invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->string('readable_id')->nullable()->index('service_dispatch_invoice_number_index');
            $table->date('date')->nullable();
            $table->double('total_amount')->default(0);
            $table->text('description')->nullable();
            $table->bigInteger('provider_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('provider_id')->references('id')->on('providers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_dispatch_invoices');
    }
}
