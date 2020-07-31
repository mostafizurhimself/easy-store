<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_supplier', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('material_id')->unsigned();
            $table->bigInteger('supplier_id')->unsigned();
            $table->foreign('material_id')->references('id')->on('materials');
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
        Schema::dropIfExists('material_supplier');
    }
}
