<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->string('name')->index('service_name_index');
            $table->string('code')->nullable()->index('service_code_index');
            $table->longText('description')->nullable();
            $table->double('rate')->default(0);
            $table->double('total_dispatch_quantity')->default(0);
            $table->double('total_receive_quantity')->default(0);
            $table->bigInteger('unit_id')->unsigned();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->string('status')->default(App\Enums\ActiveStatus::ACTIVE()->getValue());
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('category_id')->references('id')->on('service_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
