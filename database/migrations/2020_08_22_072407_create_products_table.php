<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->string('name')->index('product_name_index');
            $table->string('code')->nullable()->index('product_code_index');
            $table->longText('description')->nullable();
            $table->double('cost_price')->default(0);
            $table->double('sale_price')->default(0);
            $table->double('vat')->default(0);
            $table->double('opening_quantity')->default(0);
            $table->double('quantity')->default(0);
            $table->double('alert_quantity')->default(0);
            $table->bigInteger('unit_id')->unsigned()->nullable();
            $table->string('status')->default(App\Enums\ActiveStatus::ACTIVE()->getValue());
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('category_id')->references('id')->on('product_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proudcts');
    }
}
