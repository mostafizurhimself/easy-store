<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricReturnInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_return_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('fabric_return_invoice_no_index');
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->date('date')->index('fabric_return_invoice_date_index');
            $table->bigInteger('supplier_id')->unsigned();
            $table->double('total_return_amount')->default(0);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('fabric_return_invoices');
    }
}
