<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->string('name')->unique()->index('supplier_name_index');
            $table->string('mobile')->nullable()->index('supplier_mobile_index');
            $table->string('telephone')->nullable()->index('supplier_telephone_index');
            $table->string('email')->nullable()->index('supplier_email_index');
            $table->string('fax')->nullable();
            $table->string('vat_number')->nullable();
            $table->double('opening_balance')->default(0);
            $table->double('balance')->default(0);
            $table->string('status')->default('active');
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
        Schema::dropIfExists('suppliers');
    }
}
