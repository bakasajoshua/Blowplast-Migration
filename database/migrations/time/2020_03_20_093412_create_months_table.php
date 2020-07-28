<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LU_Month', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('month_id')->unique();
            $table->integer('month_of_year_id');
            $table->integer('year');
            $table->tinyInteger('quarter_id');

            // $table->foreign('year')->references('year')->on('LU_Year');
            // $table->foreign('month_of_year_id')->references('month_of_year_id')->on('LU_Month_Of_Year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('LU_Month');
    }
}
