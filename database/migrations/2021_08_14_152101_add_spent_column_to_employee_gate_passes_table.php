<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpentColumnToEmployeeGatePassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_gate_passes', function (Blueprint $table) {
            $table->double('spent')->default(0)->comment('In Seconds')->after('in');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_gate_passes', function (Blueprint $table) {
            $table->dropColumn('spent');
        });
    }
}