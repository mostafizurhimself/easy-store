<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubSectionIdToProductOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_outputs', function (Blueprint $table) {
            $table->dropForeign('product_outputs_floor_id_foreign');
            $table->dropColumn('floor_id');
            $table->dropForeign('product_outputs_employee_id_foreign');
            $table->dropColumn('employee_id');
            $table->bigInteger('category_id')->unsigned()->nullable()->after('date');
            $table->foreign('category_id')->references('id')->on('product_categories');
            $table->bigInteger('section_id')->unsigned()->nullable()->after('amount');
            $table->foreign('section_id')->references('id')->on('sections');
            $table->bigInteger('sub_section_id')->unsigned()->nullable()->after('section_id');
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
        Schema::table('product_outputs', function (Blueprint $table) {
            $table->bigInteger('floor_id')->unsigned()->nullable()->after('amount');
            $table->foreign('floor_id')->references('id')->on('floors');
            $table->bigInteger('employee_id')->unsigned()->nullable()->after('floor_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->dropForeign('product_outputs_category_id_foreign');
            $table->dropColumn('category_id');
            $table->dropForeign('product_outputs_sub_section_id_foreign');
            $table->dropColumn('sub_section_id');
            $table->dropForeign('product_outputs_section_id_foreign');
            $table->dropColumn('section_id');
        });
    }
}
