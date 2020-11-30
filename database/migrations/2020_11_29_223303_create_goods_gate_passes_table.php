<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsGatePassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_gate_passes', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('invoice_id')->unsigned()->nullable();
            $table->string('invoice_type')->nullable();
            $table->json('detail')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->dateTime('passed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->index(['invoice_id', 'invoice_type'], 'goods_gate_passes_invoice_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_gate_passes');
    }
}
