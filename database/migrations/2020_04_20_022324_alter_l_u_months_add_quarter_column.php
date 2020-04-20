<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLUMonthsAddQuarterColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('LU_Month', function (Blueprint $table) {
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
        //
    }
}
