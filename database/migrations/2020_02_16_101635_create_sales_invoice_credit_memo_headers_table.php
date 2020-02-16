<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoiceCreditMemoHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Sales Invoice Credit Memo Headers', function (Blueprint $table) {
            $table->string('Invoice_Credit_Memo_No')->primary();
            $table->string('Document_No')->nullable();
            $table->string('Sell-To-Customer-No');
            $table->string('Sell-To-Customer-Name')->nullable();
            $table->string('Bill-To-Customer-No');
            $table->string('Bill-To-Customer-Name')->nullable();
            $table->date('Posting_Date');
            $table->date('Due_Date')->nullable();
            $table->date('Order Date');
            $table->string('Company_Code');
            $table->enum('Type', ['Invoice', 'Credit Memo']);
            $table->decimal('Total_Amount_Excluding_Tax');
            $table->decimal('Total_Amount_Including_Tax');
            $table->string('Currency_Code');

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
        Schema::dropIfExists('sales_invoice_credit_memo_headers');
    }
}
