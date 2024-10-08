<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->bigInteger('section_id')->unsigned()->nullable();
            $table->bigInteger('sub_section_id')->unsigned()->nullable();
            $table->string('name')->index('designation_name_index');
            $table->string('code')->nullable()->index('designation_code_index');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('sub_section_id')->references('id')->on('sub_sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designations');
    }
}
