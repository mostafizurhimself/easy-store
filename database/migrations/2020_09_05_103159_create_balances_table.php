<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('expenser_id')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->double('amount')->default(0);
            $table->string('method');
            $table->string('approved_by')->nullable();
            $table->bigInteger('created_by')->unsigned();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('expenser_id')->references('id')->on('expensers');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenser_balances');
    }
}
