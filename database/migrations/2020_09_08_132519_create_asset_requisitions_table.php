<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('asset_requisition_no_index');
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->date('date')->index('asset_requisition_date_index');
            $table->double('total_requisition_amount')->default(0);
            $table->double('total_distribution_amount')->default(0);
            $table->text('note')->nullable();
            $table->bigInteger('receiver_id')->unsigned();
            $table->date('deadline')->index('asset_requisition_deadline_index');
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
        Schema::dropIfExists('asset_requisitions');
    }
}
