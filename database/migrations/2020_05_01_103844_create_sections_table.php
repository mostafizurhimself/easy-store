<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('department_id')->unsigned();
            $table->bigInteger('floor_id')->unsigned();
            $table->string('name')->index('section_name_index');
            $table->string('code')->nullable()->index('section_code_index');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('floor_id')->references('id')->on('floors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
}
