<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Item Budget', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Value_Stream')->nullable();
            $table->string('Item_Description');
            $table->string('Item_No', 50);
            $table->string('Customer_No', 50)->nullable();
            $table->string('Customer_Name')->nullable();
            $table->string('Company_Code', 10);
            $table->string('Budget_Year');
            $table->string('Budget_Month');
            $table->float('Budget_Qty_Pcs')->nullable();
            $table->float('Budget_Qty_Weight')->nullable();
            $table->float('Budget_Revenue')->nullable();

            // $table->foreign('Item_No')->references('Item_No')->on('Item Master');
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
        Schema::dropIfExists('Item Budget');
    }
}
