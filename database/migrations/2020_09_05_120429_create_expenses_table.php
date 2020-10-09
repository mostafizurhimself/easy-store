<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('expenser_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->date('date')->index('expense_date_index');
            $table->text('description')->nullable();
            $table->string('reference')->nullable()->index('expense_reference_index');
            $table->string('po_number')->nullable()->index('expense_po_number_index');
            $table->double('amount')->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('expenser_id')->references('id')->on('expensers');
            $table->foreign('category_id')->references('id')->on('expense_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
