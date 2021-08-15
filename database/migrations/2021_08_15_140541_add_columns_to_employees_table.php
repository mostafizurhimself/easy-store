<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('emergency_mobile')->nullable()->after('mobile');
            $table->string('highest_education')->nullable()->after('blood_group');
            $table->string('nid')->nullable()->after('nationality');
            $table->string('passport')->nullable()->after('nid');
            $table->string('nominee_name')->nullable()->after('passport');
            $table->string('nominee_mobile')->nullable()->after('nominee_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            //
        });
    }
}