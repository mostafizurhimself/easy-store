<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorGatePassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_gate_passes', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->string('visitor_name')->index('visitor_gate_passes_visitor_name_index');
            $table->string('mobile')->index('visitor_gate_passes_mobile_index');
            $table->string('card_no')->nullable();
            $table->text('purpose')->nullable();
            $table->bigInteger('visit_to')->unsigned()->nullable();
            $table->dateTime('in')->index('visitor_gate_passes_in_time_index');
            $table->dateTime('out')->nullable()->index('visitor_gate_passes_out_time_index');
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('visit_to')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitor_gate_passes');
    }
}
