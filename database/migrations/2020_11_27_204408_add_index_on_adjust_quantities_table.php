<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexOnAdjustQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adjust_quantities', function (Blueprint $table) {
            $table->index(['adjustable_id', 'adjustable_type'], 'adjust');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adjust_quantities', function (Blueprint $table) {
            $table->dropIndex('adjust');
        });
    }
}
