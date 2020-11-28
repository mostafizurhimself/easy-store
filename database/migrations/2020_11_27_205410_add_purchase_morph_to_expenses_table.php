<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchaseMorphToExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->bigInteger('purchase_id')->unsigned()->nullable()->after('reference');
            $table->string('purchase_type')->nullable()->after('purchase_id');
            $table->index(['purchase_id', 'purchase_type'], 'purchase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('purchase');
            $table->dropColumn('purchase_id');
            $table->dropColumn('purchase_type');
        });
    }
}
