<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempKEGLSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_k_e_g_l_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("chart of group")->nullable();
            $table->string("coa name")->nullable();
            $table->float("opening bal")->nullable();
            $table->string("opening bal type")->nullable();
            $table->string("voucher no")->nullable();
            $table->dateTime("voucher date")->nullable();
            $table->text("narration")->nullable();
            $table->string("doc no")->nullable();
            $table->date("doc date")->nullable();
            $table->float("specific amount")->nullable();
            $table->string("currency")->nullable();
            $table->float("booking rate")->nullable();
            $table->float("debit")->nullable();
            $table->float("credit")->nullable();
            $table->float("running balance")->nullable();
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
        Schema::dropIfExists('temp_k_e_g_l_s');
    }
}
