<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_supplier', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('asset_id')->unsigned();
            $table->bigInteger('supplier_id')->unsigned();
            $table->foreign('asset_id')->references('id')->on('assets');
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
        Schema::dropIfExists('asset_supplier');
    }
}
