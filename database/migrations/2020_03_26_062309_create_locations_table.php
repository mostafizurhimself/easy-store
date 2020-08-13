<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('location_readable_id_index');
            $table->string('name')->index('location_name_index');
            $table->string('type');
            $table->string('telephone')->nullable();
            $table->string('mobile')->nullable()->index('location_mobile_index');
            $table->string('email')->nullable()->index('location_email_index');
            $table->string('status');
            $table->timestamps();
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
        Schema::dropIfExists('locations');
    }
}
