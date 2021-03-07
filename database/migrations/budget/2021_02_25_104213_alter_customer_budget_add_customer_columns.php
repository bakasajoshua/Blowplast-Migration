<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomerBudgetAddCustomerColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Customer Budget', function (Blueprint $table) {
            $table->string('Customer_Name')->after('Customer_No')->nullable();
            $table->string('Customer_Value_Stream')->after('Customer_Name')->nullable();

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
        //
    }
}
