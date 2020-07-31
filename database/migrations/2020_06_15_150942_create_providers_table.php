<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->string('name')->unique()->index('provider_name_index');
            $table->string('mobile')->nullable()->index('provider_mobile_index');
            $table->string('telephone')->nullable()->index('provider_telephone_index');
            $table->string('email')->nullable()->index('provider_email_index');
            $table->string('fax')->nullable();
            $table->string('vat_number')->nullable();
            $table->double('opening_balance')->default(0);
            $table->double('balance')->default(0);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('providers');
    }
}
