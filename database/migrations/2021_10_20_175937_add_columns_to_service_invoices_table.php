<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToServiceInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_invoices', function (Blueprint $table) {
            $table->double('total_dispatch_quantity')->default(0)->after('date');
            $table->double('total_receive_quantity')->default(0)->after('total_dispatch_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_invoices', function (Blueprint $table) {
            $table->dropColumn('total_dispatch_quantity');
            $table->dropColumn('total_receive_quantity');
        });
    }
}