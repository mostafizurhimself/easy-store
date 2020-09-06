<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expensers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->string('name')->index('expenser_name_index');
            $table->string('code')->nullable()->index('expenser_code_index');
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->double('opening_balance')->default(0);
            $table->double('balance')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expensers');
    }
}
