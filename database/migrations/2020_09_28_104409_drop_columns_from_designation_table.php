<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsFromDesignationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->dropForeign('designations_department_id_foreign');
            $table->dropColumn('department_id');
            $table->dropForeign('designations_section_id_foreign');
            $table->dropColumn('section_id');
            $table->dropForeign('designations_sub_section_id_foreign');
            $table->dropColumn('sub_section_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->bigInteger('department_id')->unsigned()->nullable()->after('location_id');
            $table->bigInteger('section_id')->unsigned()->nullable()->after('department_id');
            $table->bigInteger('sub_section_id')->unsigned()->nullable()->after('section_id');

            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('sub_section_id')->references('id')->on('sub_sections');
        });
    }
}
