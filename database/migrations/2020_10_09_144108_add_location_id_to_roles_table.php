<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationIdToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->bigInteger('location_id')->unsigned()->nullable()->after('id');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign('roles_location_id_foreign');
            $table->dropColumn('location_id');
            $table->dropColumn('deleted_at');
        });
    }
}
