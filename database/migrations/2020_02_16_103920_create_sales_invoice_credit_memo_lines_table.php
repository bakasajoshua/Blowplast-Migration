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
            $table->string('Invoice_Credit_Memo_No');
            $table->string('Document_No')->nullable();
            $table->string('Item_No', 50);
            $table->decimal('Item_Weight_kg');
            $table->decimal('Item_Price_kg');
            $table->text('Item_Description')->nullable();
            $table->integer('Quantity');
            $table->decimal('Unit_Price');
            $table->decimal('Unit_Cost');
            $table->string('Company_Code');
            $table->string('Currency_Code');
            $table->enum('Type', ['Invoice', 'Credit Memo']);
            $table->decimal('Total_Amount_Excluding_Tax');
            $table->decimal('Total_Amount_Including_Tax');
            $table->string('Sales_Unit_of_Measure');
            $table->date('Posting_Date');
            $table->date('Order Date');
            $table->date('Due_Date')->nullable();

            $table->foreign('Invoice_Credit_Memo_No')->references('Invoice_Credit_Memo_No')->on('Sales Invoice Credit Memo Headers');
            $table->foreign('Item_No')->references('Item_No')->on('Inventories');
            $table->foreign('Company_Code')->references('Company_Code')->on('Companies');
            $table->foreign('Currency_Code')->references('Currency_Code')->on('Currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_invoice_credit_memo_lines');
    }
}
