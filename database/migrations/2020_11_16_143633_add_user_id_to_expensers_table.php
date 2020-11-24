<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToExpensersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expensers', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->nullable()->after('employee_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expensers', function (Blueprint $table) {
            $table->dropForeign('expensers_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}
