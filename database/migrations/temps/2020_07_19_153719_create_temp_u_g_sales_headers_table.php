<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempUGSalesHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_u_g_sales_headers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Entry_No')->nullable();
            $table->string('Customer_No')->nullable();
            $table->string('Customer_Name')->nullable();
            $table->string('Document_No')->nullable();
            $table->string('Posting_Date')->nullable();
            $table->string('Due_Date')->nullable();
            $table->string('Company_Code')->nullable();
            $table->string('Type')->nullable();
            $table->string('Total_Amount_Excluding_Tax')->nullable();
            $table->string('Total_Amount_Including_Tax')->nullable();
            $table->string('Currency_Code')->nullable();
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
        Schema::dropIfExists('temp_u_g_sales_headers');
    }
}