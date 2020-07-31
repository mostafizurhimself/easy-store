<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_categories', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->string('name')->index('fabric_category_name_index');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fabric_categories');
    }
}
