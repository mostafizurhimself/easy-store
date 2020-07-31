<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_people', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('contactable_id')->unsigned();
            $table->string('contactable_type');
            $table->string('name')->index('contact_people_name_index');
            $table->string('mobile')->index('contact_people_mobile_index');
            $table->string('telephone')->nullable()->index('contact_people_telephone_index');
            $table->string('email')->nullable()->index('contact_people_email_index');
            $table->string('designation')->nullable();
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
        Schema::dropIfExists('contact_people');
    }
}
