<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_supplier', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fabric_id')->unsigned();
            $table->bigInteger('supplier_id')->unsigned();
            $table->foreign('fabric_id')->references('id')->on('fabrics');
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
        Schema::dropIfExists('fabric_supplier');
    }
}
