<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LU_Day', function (Blueprint $table) {
            $table->bigIncrements('day_id');
            $table->integer('day');
            $table->integer('week');
            $table->integer('month');

            // $table->foreign('month')->references('month_id')->on('LU_Month');
            // $table->foreign('week')->references('week_id')->on('LU_Week');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('LU_Day');
    }
}
