<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Customer Budget', function (Blueprint $table) {
            $table->string('Customer_Budget_No');
            $table->string('Customer_No', 50)->nullable();
            $table->string('Company_Code', 10);
            $table->string('Budget_Year');
            $table->string('Budget_Month');
            $table->integer('Budget_Qty_Pcs');
            $table->float('Budget_Qty_Weight');

            // $table->foreign('Customer_No')->references('Customer_No')->on('Customer Master');
            // $table->foreign('Company_Code')->references('Company_Code')->on('Companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Customer Budget');
    }
}
