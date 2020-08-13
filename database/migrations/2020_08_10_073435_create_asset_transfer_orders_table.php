<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetTransferOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_transfer_orders', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('asset_to_number_index');
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->date('date')->index('asset_transfer_date_index');
            $table->double('total_amount')->default(0);
            $table->text('note')->nullable();
            $table->bigInteger('receiver_id')->unsigned()->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('receiver_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_transfer_orders');
    }
}
