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
            $table->json('details')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('draft');
            $table->bigInteger('passed_by')->unsigned()->nullable();
            $table->dateTime('passed_at')->nullable()->index('goods_gate_passes_passed_at_index');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('passed_by')->references('id')->on('users');
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
