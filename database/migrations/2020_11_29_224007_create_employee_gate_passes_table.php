<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeGatePassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_gate_passes', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->text('reason')->nullable();
            $table->dateTime('approved_out')->index('employee_gate_passes_approved_out_time_index');
            $table->dateTime('out')->nullable()->index('employee_gate_passes_out_time_index');
            $table->dateTime('approved_in')->nullable()->index('employee_gate_passes_approved_in_time_index');
            $table->dateTime('in')->nullable()->index('employee_gate_passes_in_time_index');
            $table->boolean('early_leave')->default(false);
            $table->string('status')->default('draft');
            $table->bigInteger('passed_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('passed_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_gate_passes');
    }
}
