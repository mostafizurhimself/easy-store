<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_sections', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('section_id')->unsigned();
            $table->string('name')->index('sub_section_name_index');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('section_id')->references('id')->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_sections');
    }
}
