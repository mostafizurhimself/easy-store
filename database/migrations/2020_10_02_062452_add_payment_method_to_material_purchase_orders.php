<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToMaterialPurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_purchase_orders', function (Blueprint $table) {
            $table->text('message')->nullable()->after('note');
            $table->string('payment_method')->nullable()->after('message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_purchase_orders', function (Blueprint $table) {
            $table->dropColumn('message');
            $table->dropColumn('payment_method');
        });
    }
}
