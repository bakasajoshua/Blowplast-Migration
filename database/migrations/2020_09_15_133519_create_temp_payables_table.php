<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempPayablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_payables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("supplier_name")->nullable();
            $table->string("credit_days")->nullable();
            $table->string("credit_limit")->nullable();
            $table->string("balance_ason")->nullable();
            $table->string("os_days")->nullable();
            $table->string("due_date")->nullable();
            $table->string("booking_rate")->nullable();
            $table->string("paid")->nullable();
            $table->string("voucher_number")->nullable();
            $table->string("voucher_date")->nullable();
            $table->string("voucher_amt")->nullable();
            $table->string("adjusted")->nullable();
            $table->string("currency")->nullable();
            $table->string("ap_amt_typ")->nullable();
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
        Schema::dropIfExists('temp_payables');
    }
}