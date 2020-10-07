<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFloorIdToProductOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_outputs', function (Blueprint $table) {
            $table->dropForeign('product_outputs_section_id_foreign');
            $table->dropForeign('product_outputs_sub_section_id_foreign');
            $table->dropColumn('section_id');
            $table->dropColumn('sub_section_id');

            $table->bigInteger('floor_id')->after('amount')->unsigned();
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
        Schema::table('product_outputs', function (Blueprint $table) {
            $table->bigInteger('section_id')->after('amount')->unsigned();
            $table->foreign('section_id')->references('id')->on('sections');

            $table->bigInteger('sub_section_id')->after('section_id')->unsigned();
            $table->foreign('sub_section_id')->references('id')->on('sections');


            $table->dropForeign('product_outputs_floor_id_foreign');
            $table->dropColumn('floor_id');
        });
    }
}
