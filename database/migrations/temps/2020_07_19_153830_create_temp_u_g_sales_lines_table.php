<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempUGSalesLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_u_g_sales_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Entry_No')->nullable();
            $table->string('LineNum')->nullable();
            $table->string('Document_No')->nullable();
            $table->string('ItemCode')->nullable();
            $table->string('Item_Weight_in_kg')->nullable();
            $table->string('Item_Price_in_kg')->nullable();
            $table->string('Item_Description')->nullable();
            $table->string('Quantity')->nullable();
            $table->string('Unit_Price')->nullable();
            $table->string('Unit_Cost')->nullable();
            $table->string('Total_Amount_Excluding_Tax')->nullable();
            $table->string('Total_Amount_Including_Tax')->nullable();
            $table->string('Sales_Unit_of_Measure')->nullable();
            $table->string('Type')->nullable();
            $table->string('Company_Code')->nullable();
            $table->string('Currency_Code')->nullable();
            $table->date('Posting_Date')->nullable();
            $table->date('Due_Date')->nullable();
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
        Schema::dropIfExists('temp_u_g_sales_lines');
    }
}