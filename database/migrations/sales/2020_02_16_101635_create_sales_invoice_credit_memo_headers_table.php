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
            $table->bigIncrements('id');
            $table->string('Invoice_Credit_Memo_No');
            $table->string('SI_Document_No')->nullable();
            $table->string('Sell-To-Customer-No')->nullable();
            $table->string('Sell-To-Customer-Name')->nullable();
            $table->string('Bill-To-Customer-No')->nullable();
            $table->string('Bill-To-Customer-Name')->nullable();
            $table->date('SI_Posting_Date')->nullable();
            $table->date('SI_Due_Date')->nullable();
            $table->date('SI_Order_Date')->nullable();
            $table->string('Company_Code')->nullable();
            $table->enum('Type', ['Invoice', 'Credit Memo', 'Credit Note', 'Direct Invoice'])->nullable();
            $table->decimal('Total_Amount_Excluding_Tax', 12, 2)->nullable();
            $table->decimal('Total_Amount_Including_Tax', 12, 2)->nullable();
            $table->string('Currency_Code')->nullable();

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
        Schema::dropIfExists('Sales Invoice Credit Memo Headers');
    }
}
