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
            $table->bigIncrements('id');
            $table->string('SI_Li_Line_No');
            $table->string('Invoice_Credit_Memo_No')->nullable();
            $table->string('SI_Li_Document_No')->nullable();
            $table->string('Item_No', 50)->nullable();
            $table->float('Item_Weight_kg', 12, 2)->nullable();
            $table->float('Item_Price_kg', 12, 2)->nullable();
            $table->text('Item_Description')->nullable();
            $table->integer('Quantity')->nullable();
            $table->float('Unit_Price', 12, 2)->nullable();
            $table->float('Unit_Cost', 12, 2)->nullable();
            $table->string('Company_Code')->nullable();
            $table->string('Currency_Code')->nullable();
            $table->enum('Type', ['Invoice', 'Credit Memo', 'Credit Note'])->nullable();
            $table->float('Total_Amount_Excluding_Tax', 12, 2)->nullable();
            $table->float('Total_Amount_Including_Tax', 12, 2)->nullable();
            $table->string('Sales_Unit_of_Measure')->nullable();
            $table->date('SI_Li_Posting_Date')->nullable();
            $table->date('SI_Li_Order_Date')->nullable();
            $table->date('SI_Li_Due_Date')->nullable();

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
