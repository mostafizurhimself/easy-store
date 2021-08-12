<?php

use App\Enums\ConfirmStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftGatePassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_gate_passes', function (Blueprint $table) {
            $table->id();
            $table->string('readable_id')->nullable();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->string('receiver_name');
            $table->integer('tshirt')->default(0);
            $table->integer('polo_tshirt')->default(0);
            $table->integer('shirt')->default(0);
            $table->integer('gaberdine_pant')->default(0);
            $table->integer('panjabi')->default(0);
            $table->integer('pajama')->default(0);
            $table->integer('kabli')->default(0);
            $table->integer('total')->default(0);
            $table->text('note')->nullable();
            $table->string('status')->default(ConfirmStatus::DRAFT());
            $table->bigInteger('passed_by')->unsigned()->nullable();
            $table->dateTime('passed_at')->nullable()->index('gift_gate_passes_passed_at_index');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('passed_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_gate_passes');
    }
}