<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ALterSalesLinesAddTimeDimensions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Sales Invoice Credit Memo Lines', function (Blueprint $table) {
            $table->date('Day')->nullable();
            $table->string('week')->nullable();
            $table->string('month')->nullable();
            $table->tinyInteger('quarter')->nullable();
            $table->integer('year')->nullable();
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
