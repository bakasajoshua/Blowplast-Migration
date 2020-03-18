<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoiceCreditMemoLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Sales Invoice Credit Memo Lines', function (Blueprint $table) {
            $table->string('Line_No')->primary();
            $table->string('Invoice_Credit_Memo_No')->nullable();
            $table->string('Document_No')->nullable();
            $table->string('Item_No', 50)->nullable();
            $table->decimal('Item_Weight_kg')->nullable();
            $table->decimal('Item_Price_kg')->nullable();
            $table->text('Item_Description')->nullable();
            $table->integer('Quantity')->nullable();
            $table->decimal('Unit_Price')->nullable();
            $table->decimal('Unit_Cost')->nullable();
            $table->string('Company_Code')->nullable();
            $table->string('Currency_Code')->nullable();
            $table->enum('Type', ['Invoice', 'Credit Memo'])->nullable();
            $table->decimal('Total_Amount_Excluding_Tax')->nullable();
            $table->decimal('Total_Amount_Including_Tax')->nullable();
            $table->string('Sales_Unit_of_Measure')->nullable();
            $table->date('Posting_Date')->nullable();
            $table->date('Order_Date')->nullable();
            $table->date('Due_Date')->nullable();

            // $table->foreign('Invoice_Credit_Memo_No')->references('Invoice_Credit_Memo_No')->on('Sales Invoice Credit Memo Headers');
            // $table->foreign('Item_No')->references('Item_No')->on('Item Master');
            // $table->foreign('Company_Code')->references('Company_Code')->on('Companies');
            // $table->foreign('Currency_Code')->references('Currency_Code')->on('Currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Sales Invoice Credit Memo Lines');
    }
}
