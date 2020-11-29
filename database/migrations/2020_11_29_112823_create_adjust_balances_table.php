<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjust_balances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('adjustable_id')->unsigned();
            $table->string('adjustable_type');
            $table->date('date');
            $table->double('amount')->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['adjustable_id', 'adjustable_type'], 'adjust_balance_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adjust_balances');
    }
}
