<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable()->index('employee_id_index');
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('department_id')->unsigned()->nullable();
            $table->bigInteger('section_id')->unsigned()->nullable();
            $table->bigInteger('sub_section_id')->unsigned()->nullable();
            $table->bigInteger('designation_id')->unsigned()->nullable();
            $table->string('first_name')->index('employee_first_name_index');
            $table->string('last_name')->nullable()->index('employee_last_name_index');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('personal_email')->nullable()->index('employee_personal_email_index');
            $table->string('mobile')->nullable()->index('employee_mobile_index');
            $table->string('telephone')->nullable()->index('employee_telephone_index');
            $table->date('joining_date')->nullable();
            $table->date('resign_date')->nullable();
            $table->double('salary')->default(0);
            $table->enum('gender', \App\Enums\Gender::toArray())->nullable();
            $table->enum('marital_status', \App\Enums\MaritalStatus::toArray())->nullable();
            $table->enum('blood_group', \App\Enums\BloodGroup::toArray())->nullable();
            $table->string('nationality')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('sub_section_id')->references('id')->on('sub_sections');
            $table->foreign('designation_id')->references('id')->on('designations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
