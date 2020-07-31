<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->string('name')->index('material_name_index');
            $table->string('code')->nullable()->index('material_code_index');
            $table->longText('description')->nullable();
            $table->double('rate')->default(0);
            $table->double('opening_quantity')->default(0);
            $table->double('quantity')->default(0);
            $table->double('alert_quantity')->default(0);
            $table->bigInteger('unit_id')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->string('status')->default(App\Enums\ActiveStatus::ACTIVE()->getValue());
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('category_id')->references('id')->on('material_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
