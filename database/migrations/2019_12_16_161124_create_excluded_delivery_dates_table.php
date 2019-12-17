<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcludedDeliveryDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excluded_delivery_dates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('city_delivery_time_id');
            $table->date('date');
            $table->foreign('city_delivery_time_id')
                    ->references('id')->on('city_delivery_times');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('excluded_delivery_dates');
    }
}
