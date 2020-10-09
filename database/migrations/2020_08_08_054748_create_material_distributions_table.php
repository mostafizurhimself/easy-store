<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('material_md_number_index');
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('material_id')->unsigned();
            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->double('amount')->default(0);
            $table->text('description')->nullable();
            $table->bigInteger('receiver_id')->unsigned();
            $table->bigInteger('unit_id')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('receiver_id')->references('id')->on('employees');
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
        Schema::dropIfExists('material_distributions');
    }
}
